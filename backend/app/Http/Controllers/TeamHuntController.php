<?php

namespace App\Http\Controllers;

use App\Helper\GifCreator;
use App\Helper\Helper;
use App\HuntItems;
use App\Hunts;
use App\HuntTeammates;
use App\Items;
use \App\Helper\GifFrameExtractor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TeamHuntController extends Controller
{

    /**
     * Global attribute for filters.
     *
     * @var
     */
    protected $filters;

    /**
     * Global attribute form items.
     *
     * @var
     */
    protected $items;

    /**
     * Global attribute for teammates.
     *
     * @var
     */
    protected $teammates;

    /**
     * Calculate the profit/waste in teamhunt.
     *
     * @return array|string
     */
    public function calculate()
    {
        $log = request()->input('loot');
        $this->filters = $this->getFilters();
        $this->items = $this->getItems();

        $this->teammates = $this->getTeammates();

        $loot = $this->getLootValue();
        $waste = $this->getWasteValue();
        $profit = $loot - $waste;
        $teammateProfit = $this->getTeammatesProfit($profit);

        $password = str_random(8);

        $hunt = Hunts::create([
            'loot'        => $log,
            'effective'   => $this->filters->effective,
            'stackable'   => $this->filters->stackable,
            'goldcoins'   => $this->filters->goldcoins,
            'valuables'   => $this->filters->valuables,
            'loot_total'  => ! empty($loot) ? $loot : 0,
            'waste_total' => ! empty($waste) ? $waste : 0,
            'password'    => $password,
            'owner'       => $this->getOwnerIP(),
        ]);

        $this->saveTeammates($hunt, $teammateProfit);
        $this->saveItems($hunt, $this->items);

        return array_merge($hunt->toArray(), ['password' => $password]);
    }

    /**
     * Return the requested hunt.
     *
     * @param Hunts $hunt
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Hunts $hunt)
    {
        if ($_SERVER['REMOTE_ADDR'] == $hunt->owner || request()->input('password') == $hunt->password) {
            $password = ['password' => $hunt->password];

            return $this->respond(array_merge(
                $hunt->toArray(),
                $password
            ));
        }

        return $this->respond(array_merge(
            $hunt->toArray()
        ));
    }

    /**
     * Update the quantity and unit price of a item.
     *
     * @param Hunts $hunt
     * @param $item
     * @return mixed
     */
    public function updateItem(Hunts $hunt, $item)
    {
        $data = (object) request()->input('params');
        if ($data->password == $hunt->password) {
            $item = $hunt->items()->where('item_id', $item)->first();

            $item->quantity = (int) $data->quantity ?: 0;
            $item->unit_price = $data->price ?: 0;

            if ($item->quantity > 0) {
                $item->save();
            } else {
                $item->delete();
            }

            return $this->recalculate(Hunts::find($hunt->id));
        }
    }

    /**
     * Update waste of a teammate in hunt.
     *
     * @param Hunts $hunt
     * @param $teammate
     * @return mixed
     */
    public function updateTeammate(Hunts $hunt, $teammate)
    {
        $data = (object) request()->input('params');
        if ($data->password == $hunt->password) {
            $teammate = $hunt->teammates()->where('id', $teammate)->first();

            $teammate->waste = (int) $data->waste ?: 0;
            $teammate->save();

            return $this->recalculate(Hunts::find($hunt->id));
        }
    }

    /**
     * Create a image with all looted items.
     *
     * @param Hunts $hunt
     */
    public function resultImage(Hunts $hunt)
    {
        $items = $hunt->items;
        $backpacks = ceil($items->count() / 20);

        $width = 178;
        $height = 213;
        $loot = imagecreatetruecolor($width * $backpacks, $height);
        imagesavealpha($loot, true);

        $background = imagecolorallocatealpha($loot, 0, 0, 0, 127);

        imagefill($loot, 0, 0, $background);

        for ($i = 1; $i <= $backpacks; $i ++) {
            $backpack = imagecreatefrompng(storage_path('app/backpack.png'));

            $column = 1;
            $line = 1;
            foreach ($items as $key => $item) {
                if ($key >= (20 * ($i - 1)) && $key < 20 * $i) {
                    $x = $column == 1 ? 8 + 5 : 8 + (5 * $column) + (32 * ($column - 1));
                    $y = $line == 1 ? 17 + 5 : 17 + (5 * $line) + (32 * ($line - 1));

                    $this->addItemToBackpack($backpack, $item, $x, $y);

                    $column = $column == 4 ? 1 : $column + 1;
                    $line = $column == 1 ? $line + 1 : $line;
                }
            }

            imagecopy($loot, $backpack, $width * ($i - 1), 0, 0, 0, $width, $height);
            imagedestroy($backpack);
        }

        header('Content-Type: image/png');
        imagepng($loot);

        imagedestroy($loot);
    }

    /**
     * Recalculate values.
     *
     * @param Hunts $hunt
     * @return mixed
     */
    private function recalculate(Hunts $hunt)
    {
        $hunt->loot_total = array_reduce($hunt->items->toArray(), function ($carry, $item) {
            return $carry + ($item['unit_price'] * $item['quantity']);
        });

        $hunt->save();

        return $hunt->loot_total;
    }

    /**
     * Add item image to backpack.
     *
     * @param $backpack
     * @param $item
     * @param $x
     * @param $y
     */
    private function addItemToBackpack($backpack, $item, $x, $y)
    {
        $slug = str_slug($item->data->title);

        if ($item->data->stackable && $item->data->category == 'Valuables') {
            switch ($item->quantity) {
                case $item->quantity >= 50:
                    $filename = "{$slug}-50";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                case $item->quantity >= 25:
                    $filename = "{$slug}-25";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                case $item->quantity >= 10:
                    $filename = "{$slug}-10";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                case $item->quantity >= 5:
                    $filename = "{$slug}-5";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                case 4:
                    $filename = "{$slug}-4";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                case 3:
                    $filename = "{$slug}-3";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                case 2:
                    $filename = "{$slug}-2";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                case 1:
                    $filename = "{$slug}-1";
                    if (file_exists(storage_path("app/items/{$filename}.gif"))) {
                        break;
                    }
                default:
                    $filename = $slug;
            }
        } else {
            $filename = $slug;
        }

        $path = storage_path("app/items/{$filename}.gif");
        $image = imagecreatefromgif($path);

        if ($item->quantity > 1) {
            $fontSize = 8;
            $font = storage_path('app/fonts/TahomaBold.ttf');
            $color = imagecolorallocate($image, 151, 151, 151);
            $shadow = imagecolorallocate($image, 0, 0, 0);

            $quantity = $item->quantity;

            $label = $this->getTextInfo($fontSize, 0, $font, $quantity);
            $labelX = imagesx($image) - abs($label->topRightX - $label->bottomLeftX);

            imagefttext($image, $fontSize, 0, $labelX + 1, 29, - $shadow, $font, $quantity);
            imagefttext($image, $fontSize, 0, $labelX - 1, 29, - $shadow, $font, $quantity);
            imagefttext($image, $fontSize, 0, $labelX, 30, - $shadow, $font, $quantity);
            imagefttext($image, $fontSize, 0, $labelX, 28, - $shadow, $font, $quantity);
            imagefttext($image, $fontSize, 0, $labelX, 29, - $color, $font, $quantity);
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        imagecopy($backpack, $image, $x, $y, 0, 0, 32, 32);
        imagedestroy($image);
    }

    /**
     * Return position data of image text resource.
     *
     * @param $fontSize
     * @param $fontAngle
     * @param $font
     * @param $text
     * @return object
     */
    private function getTextInfo($fontSize, $fontAngle, $font, $text)
    {
        $rect = imagettfbbox($fontSize, $fontAngle, $font, $text);

        return (object) [
            'bottomLeftX'  => $rect[0],
            'bottomLeftY'  => $rect[1],
            'bottomRightX' => $rect[2],
            'bottomRightY' => $rect[3],
            'topRightX'    => $rect[4],
            'topRightY'    => $rect[5],
            'topLeftX'     => $rect[6],
            'topLeftY'     => $rect[7],
            'xsize'        => abs($rect[0]) + abs($rect[2]),
            'ysize'        => abs($rect[5]) + abs($rect[1]),
        ];
    }

    /**
     * Save teammates from hunt in database.
     *
     * @param Hunts $hunt
     * @param array $teammates
     */
    private function saveTeammates(Hunts $hunt, array $teammates)
    {
        array_walk($teammates, function ($teammate, $name) use ($hunt) {
            HuntTeammates::create([
                'hunt_id'   => $hunt->id,
                'character' => empty($name) ? 'You' : $name,
                'waste'     => $teammate['waste'],
                'profit'    => $teammate['profit'],
            ]);
        });
    }

    /**
     * Save items from hunt in database.
     *
     * @param Hunts $hunt
     * @param array $items
     */
    private function saveItems(Hunts $hunt, array $items)
    {
        array_walk($items, function ($item) use ($hunt) {
            HuntItems::create([
                'hunt_id'    => $hunt->id,
                'item_id'    => $item['data']['id'],
                'quantity'   => $item['quantity'] ?: 0,
                'unit_price' => ! empty($item['data']['vendor_value']) ? $item['data']['vendor_value'] : 0,
            ]);
        });
    }

    /**
     * Get the total of waste.
     *
     * @return mixed
     */
    private function getLootValue()
    {
        return array_reduce($this->items, function ($carry, $item) {
            return $carry + ($item['data']['vendor_value'] * $item['quantity']);
        });
    }


    /**
     * Get waste of each teammate and return the total.
     *
     * @return mixed
     */
    private function getWasteValue()
    {
        return array_reduce($this->teammates, function ($carry, $teammate) {
            return $carry + $teammate['waste'];
        });

    }

    /**
     * Calculate the profit of each teammate.
     *
     * @param $profit
     * @return array
     */
    private function getTeammatesProfit($profit)
    {
        $teammateProfit = [];

        foreach ($this->teammates as $teammate) {
            $character = $teammate['name'];
            $teammateProfit[$character]['waste'] = (int) $teammate['waste'];
            $teammateProfit[$character]['profit'] = ($profit / count($this->teammates));
        }

        return $teammateProfit;
    }

    /**
     * Return the item quantity.
     *
     * @param $item
     * @return bool|int
     */
    private function getItemQuantity($item)
    {
        $firstWord = strtok($item, ' ');

        if ($firstWord == 'a' || $firstWord == 'an' || is_numeric($firstWord)) {
            return is_numeric($firstWord) ? $firstWord : 1;
        }

        return 1;
    }

    /**
     * Get the item name.
     *
     * @param $item
     * @return mixed
     */
    private function getItemName($item)
    {
        $firstWord = strtok($item, ' ');

        if ($firstWord == 'a' || $firstWord == 'an' || is_numeric($firstWord)) {
            return $this->prepareName(trim(substr($item, strpos(trim($item), " "))));
        }

        return $this->prepareName(trim($item));
    }

    /**
     * Get the filtered items.
     *
     * @param $items
     * @return array
     */
    private function filteredItems($items)
    {
        if (! $this->filters->goldcoins) {
            $items = array_filter($items, function ($item) {
                return $item['data']['name'] != 'gold coin';
            });
        }

        if ($this->filters->effective) {
            $items = array_filter($items, function ($item) {
                return ($item['data']['vendor_value'] / $item['data']['capacity']) >= 10;
            });
        }

        if (! $this->filters->stackable) {
            $items = array_filter($items, function ($item) {
                return $item['data']['category'] != 'Creature Products';
            });
        }

        if ($this->filters->valuables) {
            $items = array_filter($items, function ($item) {
                return $item['data']['vendor_value'] >= $this->filters->valuables;
            });
        }

        return $items;
    }

    /**
     * Get all items from loots.
     */
    private function getItems()
    {
        $items = [];

        foreach ($this->getLoots() as $loot) {
            foreach ($loot['items'] as $item) {
                if ($item != 'nothing') {
                    $name = $this->getItemName($item);

                    if (! Items::where('name', $name)->first()) {
                        Mail::send('itemerror', ['name' => $name], function ($message) {
                            $message->subject('Ops... item not found');
                            $message->to('ollyxpic@gmail.com');
                        });
                    } else {
                        if (! isset($items[$name])) {
                            $items[$name] = ['quantity' => 0, 'data' => Items::where('name', $name)->first()->toArray()];
                        }

                        $items[$name]['quantity'] = $items[$name]['quantity'] + $this->getItemQuantity($item);
                    }
                }
            }
        }

        return $this->filteredItems($items);
    }

    /**
     * Get loots from log and transform in a
     * array with loot text, monster, and items.
     *
     * @return array
     */
    private function getLoots()
    {
        return array_map(function ($loot) {
            return [
                'loot'    => $loot,
                'monster' => substr(explode(':', $loot)[1], 13),
                'items'   => array_map(function ($item) {
                    return trim($item);
                }, explode(',', trim(explode(':', $loot)[2]))),
            ];
        }, array_filter(explode(PHP_EOL, request()->input('loot')), function ($loot) {
            return strpos($loot, 'Loot of');
        }));
    }

    /**
     * Get teammates
     *
     * @return array
     */
    private function getTeammates()
    {
        if (request()->input('teammates')) {
            return array_filter(request()->input('teammates'), function ($teammate) {
                return ! empty($teammate['waste']);
            });
        }

        return [];
    }

    /**
     * Get filters from request and prepare them.
     *
     * @return object
     */
    private function getFilters()
    {
        $filters = request()->input('filters');

        return (object) [
            'effective' => (bool) $filters['effective'],
            'stackable' => (bool) $filters['stackable'],
            'goldcoins' => (bool) $filters['goldcoins'],
            'valuables' => $filters['valuable'] && $filters['above'] > 0 ? $filters['above'] : null,
        ];
    }

    /**
     * Prepare the itens names.
     *
     * @param $name
     * @return mixed
     */
    private function prepareName($name)
    {
        $name = trim(str_replace([
            '(active prey bonus)',
            '(common)',
            '(semi-rare)',
            '(rare)',
        ], '', $name));

        $names = [
            'gold coins'                     => 'gold coin',
            'platinum coins'                 => 'platinum coin',
            'crystal coins'                  => 'crystal coin',
            'small emeralds'                 => 'small emerald',
            'emerald bangles'                => 'emerald bangle',
            'scarab coins'                   => 'scarab coin',
            'small amethysts'                => 'small amethyst',
            'small rubys'                    => 'small ruby',
            'small diamonds'                 => 'small diamond',
            'white pearls'                   => 'white pearl',
            'black pearls'                   => 'black pearl',
            'silver broochs'                 => 'silver brooch',
            'small sapphires'                => 'small sapphire',
            'gold nuggets'                   => 'gold nugget',
            'orichalcum pearls'              => 'orichalcum pearl',
            'christmas tokens'               => 'christmas token',
            'giant shimmering pearls'        => 'giant shimmering pearl',
            'small enchanted sapphires'      => 'small enchanted sapphire',
            'small enchanted emeralds'       => 'small enchanted emerald',
            'small enchanted rubys'          => 'small enchanted ruby',
            'small enchanted amethysts'      => 'small enchanted amethyst',
            'vampire lord tokens'            => 'vampire lord token',
            'gold ingots'                    => 'gold ingot',
            'small topazes'                  => 'small topaz',
            'bar of golds'                   => 'bar of gold',
            'gooey masss'                    => 'gooey mass',
            'mucus plugs'                    => 'mucus plug',
            'blue crystal shards'            => 'blue crystal shard',
            'violet crystal shards'          => 'violet crystal shard',
            'green crystal shards'           => 'green crystal shard',
            'green crystal splinters'        => 'green crystal splinter',
            'brown crystal splinters'        => 'brown crystal splinter',
            'blue crystal splinters'         => 'blue crystal splinter',
            'cyan crystal fragments'         => 'cyan crystal fragment',
            'red crystal fragments'          => 'red crystal fragment',
            'green crystal fragments'        => 'green crystal fragment',
            'minor crystalline tokens'       => 'minor crystalline token',
            'major crystalline tokens'       => 'major crystalline token',
            'gnomish supply packages'        => 'gnomish supply package',
            'giant shimmering pearls'        => 'giant shimmering pearl',
            'giant shimmering pearls'        => 'giant shimmering pearl',
            'vampire\'s cape chains'         => 'vampire\'s cape chain',
            'golden raid tokens'             => 'golden raid token',
            'silver raid tokens'             => 'silver raid token',
            'unrealized dreams'              => 'unrealized dream',
            'glooth bags'                    => 'glooth bag',
            'golden lotus broochs'           => 'golden lotus brooch',
            'moonlight crystalss'            => 'moonlight crystals',
            'copper tokens'                  => 'copper token',
            'gold tokens'                    => 'gold token',
            'iron tokens'                    => 'iron token',
            'platinum tokens'                => 'platinum token',
            'silver tokens'                  => 'silver token',
            'titanium tokens'                => 'titanium token',
            'onyx chips'                     => 'onyx chip',
            'opals'                          => 'opal',
            'shaggy ogre bags'               => 'shaggy ogre bag',
            'arena badges'                   => 'arena badge',
            'solid rages'                    => 'solid rage',
            'prismatic quartzs'              => 'prismatic quartz',
            'tiger eyes'                     => 'tiger eye',
            'berserk potions'                => 'berserk potion',
            'bullseye potions'               => 'bullseye potion',
            'mastermind potions'             => 'mastermind potion',
            'strong mana potions'            => 'strong mana potion',
            'great mana potions'             => 'great mana potion',
            'strong health potions'          => 'strong health potion',
            'great health potions'           => 'great health potion',
            'mana potions'                   => 'mana potion',
            'health potions'                 => 'health potion',
            'great spirit potions'           => 'great spirit potion',
            'ultimate health potions'        => 'ultimate health potion',
            'small health potions'           => 'small health potion',
            'antidote potions'               => 'antidote potion',
            'flask of rust removers'         => 'flask of rust remover',
            'muck removers'                  => 'muck remover',
            'supreme health potions'         => 'supreme health potion',
            'ultimate mana potions'          => 'ultimate mana potion',
            'ultimate spirit potions'        => 'ultimate spirit potion',
            'tainted glooth capsules'        => 'tainted glooth capsule',
            'glooth capsules'                => 'glooth capsule',
            'glooth spears'                  => 'glooth spear',
            'glooth steaks'                  => 'glooth steak',
            'glooth sandwiches'              => 'glooth sandwich',
            'red apples'                     => 'red apple',
            'worms'                          => 'worm',
            'poison arrows'                  => 'poison arrow',
            'burst arrows'                   => 'burst arrow',
            'dark rosaries'                  => 'dark rosary',
            'assassin stars'                 => 'assassin star',
            'werewolf fang'                  => 'werewolf fangs',
            'bat wings'                      => 'bat wing',
            'fish remains'                   => 'remains of a fish',
            'potatos'                        => 'potato',
            'potatoes'                       => 'potato',
            'blank runes'                    => 'blank rune',
            'royal spears'                   => 'royal spear',
            'hunting spears'                 => 'hunting spear',
            'throwing stars'                 => 'throwing star',
            'bolts'                          => 'bolt',
            'white mushrooms'                => 'white mushroom',
            'red mushrooms'                  => 'red mushroom',
            'brown mushrooms'                => 'brown mushroom',
            'fire mushrooms'                 => 'fire mushroom',
            'green mushrooms'                => 'green mushroom',
            'dark mushrooms'                 => 'dark mushroom',
            'small stones'                   => 'small stone',
            'shiver arrows'                  => 'shiver arrow',
            'tusks'                          => 'tusk',
            'arrows'                         => 'arrow',
            'sniper arrows'                  => 'sniper arrow',
            'stars'                          => 'star',
            'small rubies'                   => 'small ruby',
            'nails'                          => 'nail',
            'waspoid wings'                  => 'waspoid wing',
            'flash arrows'                   => 'flash arrow',
            'crystalline arrows'             => 'crystalline arrow',
            'diamond arrows'                 => 'diamond arrow',
            'earth arrows'                   => 'earth arrow',
            'envenomed arrows'               => 'envenomed arrow',
            'flaming arrows'                 => 'flaming arrow',
            'onyx arrows'                    => 'onyx arrow',
            'poison arrows'                  => 'poison arrow',
            'shiver arrows'                  => 'shiver arrow',
            'simple arrows'                  => 'simple arrow',
            'sniper arrows'                  => 'sniper arrow',
            'tarsal arrows'                  => 'tarsal arrow',
            'bolts'                          => 'bolt',
            'drill bolts'                    => 'drill bolt',
            'infernal bolts'                 => 'infernal bolt',
            'piercing bolts'                 => 'piercing bolt',
            'power bolts'                    => 'power bolt',
            'prismatic bolts'                => 'prismatic bolt',
            'spectral bolts'                 => 'spectral bolt',
            'vortex bolts'                   => 'vortex bolt',
            'spears'                         => 'spear',
            'royal spears'                   => 'royal spear',
            'enchanted spears'               => 'enchanted spear',
            'hunting spears'                 => 'hunting spear',
            'prismatic bolts'                => 'prismatic bolt',
            "star herbs"                     => "star herb",
            "stone herbs"                    => "stone herb",
            "small oil lamps"                => "small oil lamp",
            "strange symbols"                => "strange symbol",
            "tusks"                          => "tusk",
            "hydra eggs"                     => "hydra egg",
            "bat wings"                      => "bat wing",
            "honeycombs"                     => "honeycomb",
            "brown piece of cloths"          => "brown piece of cloth",
            "green dragon leathers"          => "green dragon leather",
            "enchanted chicken wings"        => "enchanted chicken wing",
            "green dragon scales"            => "green dragon scale",
            "iron ores"                      => "iron ore",
            "minotaur leathers"              => "minotaur leather",
            "bonelord eyes"                  => "bonelord eye",
            "bear paws"                      => "bear paw",
            "wolf paws"                      => "wolf paw",
            "white piece of cloths"          => "white piece of cloth",
            "red dragon scales"              => "red dragon scale",
            "demon horns"                    => "demon horn",
            "chicken feathers"               => "chicken feather",
            "woods"                          => "wood",
            "ape furs"                       => "ape fur",
            "red dragon leathers"            => "red dragon leather",
            "perfect behemoth fangs"         => "perfect behemoth fang",
            "turtle shells"                  => "turtle shell",
            "heaven blossoms"                => "heaven blossom",
            "yellow piece of cloths"         => "yellow piece of cloth",
            "sniper glovess"                 => "sniper gloves",
            "dragon claws"                   => "dragon claw",
            "lizard leathers"                => "lizard leather",
            "lizard scales"                  => "lizard scale",
            "fish fins"                      => "fish fin",
            "vampire dusts"                  => "vampire dust",
            "demon dusts"                    => "demon dust",
            "hardened bones"                 => "hardened bone",
            "peg legs"                       => "peg leg",
            "hooks"                          => "hook",
            "eye patchs"                     => "eye patch",
            "blue piece of cloths"           => "blue piece of cloth",
            "green piece of cloths"          => "green piece of cloth",
            "behemoth claws"                 => "behemoth claw",
            "red piece of cloths"            => "red piece of cloth",
            "nose rings"                     => "nose ring",
            "soul stones"                    => "soul stone",
            "magic sulphurs"                 => "magic sulphur",
            "flask of warrior's sweats"      => "flask of warrior's sweat",
            "dwarven beards"                 => "dwarven beard",
            "Orshabaal's brains"             => "Orshabaal's brain",
            "Mr. Punish's handcuffss"        => "Mr. Punish's handcuffs",
            "cat's paws"                     => "cat's paw",
            "Dracola's eyes"                 => "Dracola's eye",
            "the Imperor's tridents"         => "the Imperor's trident",
            "the Plasmother's remainss"      => "the Plasmother's remains",
            "the Handmaiden's protectors"    => "the Handmaiden's protector",
            "Countess Sorrow's frozen tears" => "Countess Sorrow's frozen tear",
            "shards"                         => "shard",
            "Morgaroth's hearts"             => "Morgaroth's heart",
            "piece of Massacre's shells"     => "piece of Massacre's shell",
            "seedss"                         => "seeds",
            "technomancer beards"            => "technomancer beard",
            "cockroach legs"                 => "cockroach leg",
            "orc tusks"                      => "orc tusk",
            "glands"                         => "gland",
            "glob of mercurys"               => "glob of mercury",
            "glob of acid slimes"            => "glob of acid slime",
            "glob of tars"                   => "glob of tar",
            "spider silks"                   => "spider silk",
            "egg of The Manys"               => "egg of The Many",
            "bunch of troll hairs"           => "bunch of troll hair",
            "lump of dirts"                  => "lump of dirt",
            "orc tooths"                     => "orc tooth",
            "spider fangss"                  => "spider fangs",
            "scarab pincerss"                => "scarab pincers",
            "shaggy tails"                   => "shaggy tail",
            "sandcrawler shells"             => "sandcrawler shell",
            "zaogun shoulderplatess"         => "zaogun shoulderplates",
            "warwolf furs"                   => "warwolf fur",
            "bony tails"                     => "bony tail",
            "crab pincerss"                  => "crab pincers",
            "half-digested piece of meats"   => "half-digested piece of meat",
            "hellspawn tails"                => "hellspawn tail",
            "piece of crocodile leathers"    => "piece of crocodile leather",
            "mutated fleshs"                 => "mutated flesh",
            "essence of a bad dreams"        => "essence of a bad dream",
            "book of necromantic ritualss"   => "book of necromantic rituals",
            "tarantula eggs"                 => "tarantula egg",
            "lump of earths"                 => "lump of earth",
            "centipede legs"                 => "centipede leg",
            "hydra heads"                    => "hydra head",
            "spiked iron balls"              => "spiked iron ball",
            "snake skins"                    => "snake skin",
            "bone shoulderplates"            => "bone shoulderplate",
            "sulphurous stones"              => "sulphurous stone",
            "boggy dreadss"                  => "boggy dreads",
            "dark rosarys"                   => "dark rosary",
            "rotten piece of cloths"         => "rotten piece of cloth",
            "mammoth tusks"                  => "mammoth tusk",
            "hellhound slobbers"             => "hellhound slobber",
            "mutated bat ears"               => "mutated bat ear",
            "broken gladiator shields"       => "broken gladiator shield",
            "carniphila seedss"              => "carniphila seeds",
            "carrion worm fangs"             => "carrion worm fang",
            "bundle of cursed straws"        => "bundle of cursed straw",
            "cobra tongues"                  => "cobra tongue",
            "compasss"                       => "compass",
            "weaver's wandtips"              => "weaver's wandtip",
            "badger furs"                    => "badger fur",
            "acorns"                         => "acorn",
            "ancient stones"                 => "ancient stone",
            "black hoods"                    => "black hood",
            "book of prayerss"               => "book of prayers",
            "cultish masks"                  => "cultish mask",
            "cultish robes"                  => "cultish robe",
            "demonic skeletal hands"         => "demonic skeletal hand",
            "dragon priest's wandtips"       => "dragon priest's wandtip",
            "elder bonelord tentacles"       => "elder bonelord tentacle",
            "elvish talismans"               => "elvish talisman",
            "fiery hearts"                   => "fiery heart",
            "frosty hearts"                  => "frosty heart",
            "frost giant pelts"              => "frost giant pelt",
            "gauze bandages"                 => "gauze bandage",
            "gear crystals"                  => "gear crystal",
            "ghastly dragon heads"           => "ghastly dragon head",
            "ghostly tissues"                => "ghostly tissue",
            "giant eyes"                     => "giant eye",
            "petrified screams"              => "petrified scream",
            "nettle blossoms"                => "nettle blossom",
            "mystical hourglasss"            => "mystical hourglass",
            "mutated rat tails"              => "mutated rat tail",
            "metal spikes"                   => "metal spike",
            "lion's manes"                   => "lion's mane",
            "half-eaten brains"              => "half-eaten brain",
            "piece of dead brains"           => "piece of dead brain",
            "piece of hellfire armors"       => "piece of hellfire armor",
            "piece of scarab shells"         => "piece of scarab shell",
            "pig foots"                      => "pig foot",
            "poisonous slimes"               => "poisonous slime",
            "polar bear paws"                => "polar bear paw",
            "scorpion tails"                 => "scorpion tail",
            "sea serpent scales"             => "sea serpent scale",
            "scythe legs"                    => "scythe leg",
            "vampire teeths"                 => "vampire teeth",
            "unholy bones"                   => "unholy bone",
            "undead hearts"                  => "undead heart",
            "thorns"                         => "thorn",
            "thick furs"                     => "thick fur",
            "tattered piece of robes"        => "tattered piece of robe",
            "swamp grasss"                   => "swamp grass",
            "striped furs"                   => "striped fur",
            "strand of medusa hairs"         => "strand of medusa hair",
            "stone wings"                    => "stone wing",
            "silky furs"                     => "silky fur",
            "skunk tails"                    => "skunk tail",
            "shiny stones"                   => "shiny stone",
            "war crystals"                   => "war crystal",
            "werewolf furs"                  => "werewolf fur",
            "widow's mandibless"             => "widow's mandibles",
            "winged tails"                   => "winged tail",
            "winter wolf furs"               => "winter wolf fur",
            "witch brooms"                   => "witch broom",
            "wools"                          => "wool",
            "wyrm scales"                    => "wyrm scale",
            "wyvern talismans"               => "wyvern talisman",
            "lancer beetle shells"           => "lancer beetle shell",
            "red lanterns"                   => "red lantern",
            "legionnaire flagss"             => "legionnaire flags",
            "high guard shoulderplatess"     => "high guard shoulderplates",
            "antlerss"                       => "antlers",
            "bloody pincerss"                => "bloody pincers",
            "cyclops toes"                   => "cyclops toe",
            "frosty ear of a trolls"         => "frosty ear of a troll",
            "sabretooths"                    => "sabretooth",
            "terramite legss"                => "terramite legs",
            "terramite shells"               => "terramite shell",
            "terrorbird beaks"               => "terrorbird beak",
            "broken halberds"                => "broken halberd",
            "warmaster's wristguardss"       => "warmaster's wristguards",
            "cursed shoulder spikess"        => "cursed shoulder spikes",
            "high guard flags"               => "high guard flag",
            "corrupted flags"                => "corrupted flag",
            "haunted piece of woods"         => "haunted piece of wood",
            "zaogun flags"                   => "zaogun flag",
            "spooky blue eyes"               => "spooky blue eye",
            "bamboo sticks"                  => "bamboo stick",
            "banana sashs"                   => "banana sash",
            "hair of a banshees"             => "hair of a banshee",
            "battle stones"                  => "battle stone",
            "black wools"                    => "black wool",
            "blood preservations"            => "blood preservation",
            "small notebooks"                => "small notebook",
            "broken crossbows"               => "broken crossbow",
            "broken helmets"                 => "broken helmet",
            "broken key rings"               => "broken key ring",
            "broken shamanic staffs"         => "broken shamanic staff",
            "colourful feathers"             => "colourful feather",
            "cultish symbols"                => "cultish symbol",
            "dirty turbans"                  => "dirty turban",
            "downy feathers"                 => "downy feather",
            "dragon's tails"                 => "dragon's tail",
            "elven astral observers"         => "elven astral observer",
            "elven scouting glasss"          => "elven scouting glass",
            "flask of embalming fluids"      => "flask of embalming fluid",
            "geomancer's robes"              => "geomancer's robe",
            "geomancer's staffs"             => "geomancer's staff",
            "ghoul snacks"                   => "ghoul snack",
            "girlish hair decorations"       => "girlish hair decoration",
            "goblin ears"                    => "goblin ear",
            "hunter's quivers"               => "hunter's quiver",
            "jewelled belts"                 => "jewelled belt",
            "kongra's shoulderpads"          => "kongra's shoulderpad",
            "luminous orbs"                  => "luminous orb",
            "mantassin tails"                => "mantassin tail",
            "minotaur horns"                 => "minotaur horn",
            "miraculums"                     => "miraculum",
            "necromantic robes"              => "necromantic robe",
            "nettle spits"                   => "nettle spit",
            "noble turbans"                  => "noble turban",
            "orc leathers"                   => "orc leather",
            "orcish gears"                   => "orcish gear",
            "pelvis bones"                   => "pelvis bone",
            "piece of archer armors"         => "piece of archer armor",
            "piece of warrior armors"        => "piece of warrior armor",
            "pile of grave earths"           => "pile of grave earth",
            "poison spider shells"           => "poison spider shell",
            "protective charms"              => "protective charm",
            "purple robes"                   => "purple robe",
            "quara bones"                    => "quara bone",
            "quara eyes"                     => "quara eye",
            "quara pincerss"                 => "quara pincers",
            "quara tentacles"                => "quara tentacle",
            "rope belts"                     => "rope belt",
            "safety pins"                    => "safety pin",
            "scroll of heroic deedss"        => "scroll of heroic deeds",
            "shamanic hoods"                 => "shamanic hood",
            "small flask of eyedropss"       => "small flask of eyedrops",
            "small pitchforks"               => "small pitchfork",
            "trollroots"                     => "trollroot",
            "brimstone shells"               => "brimstone shell",
            "brimstone fangss"               => "brimstone fangs",
            "draken sulphurs"                => "draken sulphur",
            "eye of corruptions"             => "eye of corruption",
            "lizard essences"                => "lizard essence",
            "scale of corruptions"           => "scale of corruption",
            "tail of corruptions"            => "tail of corruption",
            "tentacle pieces"                => "tentacle piece",
            "skull belts"                    => "skull belt",
            "draken wristbandss"             => "draken wristbands",
            "broken slicers"                 => "broken slicer",
            "broken draken mails"            => "broken draken mail",
            "panther paws"                   => "panther paw",
            "stampor horns"                  => "stampor horn",
            "stampor talonss"                => "stampor talons",
            "hollow stampor hoofs"           => "hollow stampor hoof",
            "draptor scaless"                => "draptor scales",
            "panther heads"                  => "panther head",
            "maxillas"                       => "maxilla",
            "giant crab pincers"             => "giant crab pincer",
            "cavebear skulls"                => "cavebear skull",
            "eye of a deeplings"             => "eye of a deepling",
            "slime moulds"                   => "slime mould",
            "yielockss"                      => "yielocks",
            "yielowaxs"                      => "yielowax",
            "white deer skins"               => "white deer skin",
            "white deer antlerss"            => "white deer antlers",
            "flintstones"                    => "flintstone",
            "demonic fingers"                => "demonic finger",
            "coals"                          => "coal",
            "broken ring of endings"         => "broken ring of ending",
            "ominous piece of cloths"        => "ominous piece of cloth",
            "ludicrous piece of cloths"      => "ludicrous piece of cloth",
            "voluminous piece of cloths"     => "voluminous piece of cloth",
            "luminous piece of cloths"       => "luminous piece of cloth",
            "obvious piece of cloths"        => "obvious piece of cloth",
            "dubious piece of cloths"        => "dubious piece of cloth",
            "elemental spikess"              => "elemental spikes",
            "deepling claws"                 => "deepling claw",
            "crawler head platings"          => "crawler head plating",
            "deepling breaktime snacks"      => "deepling breaktime snack",
            "deepling guard belt buckles"    => "deepling guard belt buckle",
            "deepling ridges"                => "deepling ridge",
            "deepling scaless"               => "deepling scales",
            "deepling wartss"                => "deepling warts",
            "compound eyes"                  => "compound eye",
            "spidris mandibles"              => "spidris mandible",
            "spitter noses"                  => "spitter nose",
            "swarmer antennas"               => "swarmer antenna",
            "waspoid claws"                  => "waspoid claw",
            "waspoid wings"                  => "waspoid wing",
            "deeptagss"                      => "deeptags",
            "dung balls"                     => "dung ball",
            "key to the Drowned Librarys"    => "key to the Drowned Library",
            "kollos shells"                  => "kollos shell",
            "spellsinger's seals"            => "spellsinger's seal",
            "Jaul's Pearls"                  => "Jaul's Pearl",
            "Obujos' Shells"                 => "Obujos' Shell",
            "Tanjis' Sights"                 => "Tanjis' Sight",
            "magma clumps"                   => "magma clump",
            "blazing bones"                  => "blazing bone",
            "eye of a weepers"               => "eye of a weeper",
            "pulverized ores"                => "pulverized ore",
            "cliff strider claws"            => "cliff strider claw",
            "vein of ores"                   => "vein of ore",
            "stone noses"                    => "stone nose",
            "crystalline spikess"            => "crystalline spikes",
            "humongous chunks"               => "humongous chunk",
            "hideous chunks"                 => "hideous chunk",
            "damselfly wings"                => "damselfly wing",
            "damselfly eyes"                 => "damselfly eye",
            "marsh stalker beaks"            => "marsh stalker beak",
            "marsh stalker feathers"         => "marsh stalker feather",
            "piece of swampling woods"       => "piece of swampling wood",
            "lost basher's spikes"           => "lost basher's spike",
            "Lost Husher's Staffs"           => "Lost Husher's Staff",
            "basalt fetishs"                 => "basalt fetish",
            "basalt figurines"               => "basalt figurine",
            "cheese cutters"                 => "cheese cutter",
            "cheesy figurines"               => "cheesy figurine",
            "earflaps"                       => "earflap",
            "bolas"                          => "bola",
            "holy ashs"                      => "holy ash",
            "lost bracerss"                  => "lost bracers",
            "mad froths"                     => "mad froth",
            "red hair dyes"                  => "red hair dye",
            "skull shatterers"               => "skull shatterer",
            "swampling mosss"                => "swampling moss",
            "wimp tooth chains"              => "wimp tooth chain",
            "bone fetishs"                   => "bone fetish",
            "bonecarving knifes"             => "bonecarving knife",
            "bloody dwarven beards"          => "bloody dwarven beard",
            "broken throwing axes"           => "broken throwing axe",
            "tooth files"                    => "tooth file",
            "lancets"                        => "lancet",
            "horoscopes"                     => "horoscope",
            "incantation notess"             => "incantation notes",
            "rorc feathers"                  => "rorc feather",
            "elven hoofs"                    => "elven hoof",
            "venisons"                       => "venison",
            "rorc eggs"                      => "rorc egg",
            "hatched rorc eggs"              => "hatched rorc egg",
            "dowsers"                        => "dowser",
            "fir cones"                      => "fir cone",
            "blood tincture in a vials"      => "blood tincture in a vial",
            "pieces of magic chalks"         => "pieces of magic chalk",
            "sight of surrender's eyes"      => "sight of surrender's eye",
            "frazzle tongues"                => "frazzle tongue",
            "frazzle skins"                  => "frazzle skin",
            "silencer clawss"                => "silencer claws",
            "silencer resonating chambers"   => "silencer resonating chamber",
            "dead weights"                   => "dead weight",
            "trapped bad dream monsters"     => "trapped bad dream monster",
            "bowl of terror sweats"          => "bowl of terror sweat",
            "goosebump leathers"             => "goosebump leather",
            "hemp ropes"                     => "hemp rope",
            "pool of chitinous glues"        => "pool of chitinous glue",
            "slime hearts"                   => "slime heart",
            "metal jaws"                     => "metal jaw",
            "glooth injection tubes"         => "glooth injection tube",
            "poisoned fangs"                 => "poisoned fang",
            "necromantic rusts"              => "necromantic rust",
            "slimy leaf tentacles"           => "slimy leaf tentacle",
            "metal toes"                     => "metal toe",
            "giant pacifiers"                => "giant pacifier",
            "moohtant horns"                 => "moohtant horn",
            "execowtioner masks"             => "execowtioner mask",
            "mooh'tah shells"                => "mooh'tah shell",
            "cowbells"                       => "cowbell",
            "glob of glooths"                => "glob of glooth",
            "seacrest hairs"                 => "seacrest hair",
            "seacrest pearls"                => "seacrest pearl",
            "seacrest scales"                => "seacrest scale",
            "gloom wolf furs"                => "gloom wolf fur",
            "wereboar tuskss"                => "wereboar tusks",
            "wereboar hoovess"               => "wereboar hooves",
            "werewolf fangss"                => "werewolf fangs",
            "werebadger skulls"              => "werebadger skull",
            "werebear skulls"                => "werebear skull",
            "werebear furs"                  => "werebear fur",
            "werebadger clawss"              => "werebadger claws",
            "ogre ear studs"                 => "ogre ear stud",
            "ogre nose rings"                => "ogre nose ring",
            "pair of hellflayer hornss"      => "pair of hellflayer horns",
            "shamanic talismans"             => "shamanic talisman",
            "some grimeleech wingss"         => "some grimeleech wings",
            "vexclaw talons"                 => "vexclaw talon",
            "skull fetishs"                  => "skull fetish",
            "crystallized angers"            => "crystallized anger",
            "frozen lightnings"              => "frozen lightning",
            "instable proto matters"         => "instable proto matter",
            "spark spheres"                  => "spark sphere",
            "plasmatic lightnings"           => "plasmatic lightning",
            "energy veins"                   => "energy vein",
            "sparkion claws"                 => "sparkion claw",
            "sparkion tails"                 => "sparkion tail",
            "sparkion legss"                 => "sparkion legs",
            "curious matters"                => "curious matter",
            "plasma pearlss"                 => "plasma pearls",
            "volatile proto matters"         => "volatile proto matter",
            "odd organs"                     => "odd organ",
            "energy balls"                   => "energy ball",
            "condensed energys"              => "condensed energy",
            "crystal bones"                  => "crystal bone",
            "sparkion stingss"               => "sparkion stings",
            "small energy balls"             => "small energy ball",
            "strange proto matters"          => "strange proto matter",
            "dangerous proto matters"        => "dangerous proto matter",
            "glistening bones"               => "glistening bone",
            "coal eyess"                     => "coal eyes",
            "glowing carrots"                => "glowing carrot",
            "astral glyphs"                  => "astral glyph",
            "astral sources"                 => "astral source",
            "shadow paints"                  => "shadow paint",
            "shadow masks"                   => "shadow mask",
            "key to knowledges"              => "key to knowledge",
            "forbidden tomes"                => "forbidden tome",
            "frozen times"                   => "frozen time",
            "ancient watchs"                 => "ancient watch",
            "golden talons"                  => "golden talon",
            "dragon crowns"                  => "dragon crown",
            "thorn seeds"                    => "thorn seed",
            "bone toothpicks"                => "bone toothpick",
            "beetle carapaces"               => "beetle carapace",
            "bug meats"                      => "bug meat",
            "ancient belt buckles"           => "ancient belt buckle",
            "cracked alabaster vases"        => "cracked alabaster vase",
            "rhino horn carvings"            => "rhino horn carving",
            "tarnished rhino figurines"      => "tarnished rhino figurine",
            "rhino hides"                    => "rhino hide",
            "rhino horns"                    => "rhino horn",
            "ancient coins"                  => "ancient coin",
            "coral broochs"                  => "coral brooch",
            "dragon tongues"                 => "dragon tongue",
            "blueberrys" => "blueberry",
            "cheeses" => "cheese",
            "fishs" => "fish",
            "hams" => "ham",
            "meats" => "meat",
            "red apples" => "red apple",
            "carrots" => "carrot",
            "coconuts" => "coconut",
            "bananas" => "banana",
            "oranges" => "orange",
            "white mushrooms" => "white mushroom",
            "eggs" => "egg",
            "breads" => "bread",
            "cherrys" => "cherry",
            "brown breads" => "brown bread",
            "brown mushrooms" => "brown mushroom",
            "candy canes" => "candy cane",
            "cookies" => "cookie",
            "corncobs" => "corncob",
            "dark mushrooms" => "dark mushroom",
            "dragon hams" => "dragon ham",
            "fire mushrooms" => "fire mushroom",
            "grapess" => "grapes",
            "green mushrooms" => "green mushroom",
            "salmons" => "salmon",
            "melons" => "melon",
            "orange mushrooms" => "orange mushroom",
            "pears" => "pear",
            "shrimps" => "shrimp",
            "red mushrooms" => "red mushroom",
            "rolls" => "roll",
            "strawberrys" => "strawberry",
            "tomatos" => "tomato",
            "wood mushrooms" => "wood mushroom",
            "pumpkins" => "pumpkin",
            "flours" => "flour",
            "lump of doughs" => "lump of dough",
            "tortoise eggs" => "tortoise egg",
            "mangos" => "mango",
            "some mushroomss" => "some mushrooms",
            "some mushroomss" => "some mushrooms",
            "gingerbreadmans" => "gingerbreadman",
            "lump of cake doughs" => "lump of cake dough",
            "cakes" => "cake",
            "cream cakes" => "cream cake",
            "valentine's cakes" => "valentine's cake",
            "cakes" => "cake",
            "bar of chocolates" => "bar of chocolate",
            "candys" => "candy",
            "the carrot of dooms" => "the carrot of doom",
            "coloured eggs" => "coloured egg",
            "coloured eggs" => "coloured egg",
            "coloured eggs" => "coloured egg",
            "coloured eggs" => "coloured egg",
            "coloured eggs" => "coloured egg",
            "rainbow trouts" => "rainbow trout",
            "northern pikes" => "northern pike",
            "green perchs" => "green perch",
            "scarab cheeses" => "scarab cheese",
            "walnuts" => "walnut",
            "peanuts" => "peanut",
            "ice cream cones" => "ice cream cone",
            "marlins" => "marlin",
            "ice cream cones" => "ice cream cone",
            "ice cream cones" => "ice cream cone",
            "ice cream cones" => "ice cream cone",
            "raspberrys" => "raspberry",
            "lemons" => "lemon",
            "plums" => "plum",
            "cucumbers" => "cucumber",
            "beetroots" => "beetroot",
            "onions" => "onion",
            "jalapeño peppers" => "jalapeño pepper",
            "chocolate cakes" => "chocolate cake",
            "lump of chocolate doughs" => "lump of chocolate dough",
            "baking trays" => "baking tray",
            "tortoise egg from nargors" => "tortoise egg from nargor",
            "yummy gummy worms" => "yummy gummy worm",
            "ice cream cones" => "ice cream cone",
            "bulb of garlics" => "bulb of garlic",
            "blessed steaks" => "blessed steak",
            "veggie casseroles" => "veggie casserole",
            "filled jalapeño pepperss" => "filled jalapeño peppers",
            "tropical fried terrorbirds" => "tropical fried terrorbird",
            "roasted dragon wingss" => "roasted dragon wings",
            "northern fishburgers" => "northern fishburger",
            "rotworm stews" => "rotworm stew",
            "carrot cakes" => "carrot cake",
            "hydra tongue salads" => "hydra tongue salad",
            "potatos" => "potato",
            "rice balls" => "rice ball",
            "terramite eggss" => "terramite eggs",
            "crocodile steaks" => "crocodile steak",
            "hydra meats" => "hydra meat",
            "aubergines" => "aubergine",
            "broccolis" => "broccoli",
            "cauliflowers" => "cauliflower",
            "dragonfruits" => "dragonfruit",
            "lettuces" => "lettuce",
            "peass" => "peas",
            "pineapples" => "pineapple",
            "starfruits" => "starfruit",
            "coconut shrimp bakes" => "coconut shrimp bake",
            "pot of blackjacks" => "pot of blackjack",
            "ectoplasmic sushis" => "ectoplasmic sushi",
            "demonic candy balls" => "demonic candy ball",
            "haunch of boars" => "haunch of boar",
            "deepling filets" => "deepling filet",
            "larvaes" => "larvae",
            "sandfishs" => "sandfish",
            "ice cream cones" => "ice cream cone",
            "ice cream cones" => "ice cream cone",
            "anniversary cakes" => "anniversary cake",
            "cheese cookies" => "cheese cookie",
            "mushroom pies" => "mushroom pie",
            "insectoid eggss" => "insectoid eggs",
            "rat cheeses" => "rat cheese",
            "soft cheeses" => "soft cheese",
            "christmas cookie trays" => "christmas cookie tray",
            "glooth sandwichs" => "glooth sandwich",
            "bottle of glooth wines" => "bottle of glooth wine",
            "bowl of glooth soups" => "bowl of glooth soup",
            "glooth steaks" => "glooth steak",
            "Raw Meats" => "Raw Meat",
            "roasted meats" => "roasted meat",
            "prickly pears" => "prickly pear",
            "energy drinks" => "energy drink",
            "energy bars" => "energy bar",
            "forbidden fruits" => "forbidden fruit",
            "cave turnips" => "cave turnip",
        ];

        return isset($names[$name]) ? $names[$name] : $name;
    }

    private function getOwnerIP()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}
