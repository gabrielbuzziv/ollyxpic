<?php

namespace App\Http\Controllers;

use App\Helper\GifCreator;
use App\Helper\Helper;
use App\HuntItems;
use App\Hunts;
use App\HuntTeammates;
use App\Item;
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
     * Error
     *
     * @var array
     */
    protected $error = [];

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

        if (count($this->error) > 0) {
            Mail::send('itemerror', ['errors' => $this->error, 'log' => request()->input('loot')], function ($message) {
                $message->subject('Ops... Loot count error');
                $message->to('ollyxpic@gmail.com');
            });
        }

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

            $hunt = Hunts::with('items.data.sells.npc', 'items.data.buys.npc', 'teammates')->find($hunt->id);

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

        $loots = array_filter($this->getLoots(), function ($loot) {
            return count($loot) > 0;
        });

        foreach ($loots as $loot) {
            foreach ($loot['items'] as $item) {
                if ($item != 'nothing') {
                    $name = $this->getItemName($item);

                    if (! Item::where('name', $name)->first()) {
                        $this->error[] = $name;

                    } else {
                        if (! isset($items[$name])) {
                            $items[$name] = ['quantity' => 0, 'data' => Item::where('name', $name)->first()->toArray()];
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
        // Loots
        $loots = array_map(function ($loot) {
            return [
                'loot'    => trim($loot),
                'monster' => substr(explode(':', $loot)[1], 13),
                'items'   => array_map(function ($item) {
                    return trim($item);
                }, explode(',', trim(explode(':', $loot)[2]))),
            ];
        }, array_filter(explode(PHP_EOL, request()->input('loot')), function ($loot) {
            return strpos($loot, 'Loot of');
        }));

        // Look At
        if (request()->input('loot_at')) {
            $items = array_map(function ($loot) {
                return array_map(function ($l) {
                    return trim($l);
                }, array_filter(explode(PHP_EOL, $loot), function ($l) {
                    return ! empty($l);
                }));
            }, array_filter(preg_split('/\b\d{2}:\d{2}\b/', request()->input('loot')), function ($loot) {
                return ! empty($loot) ? trim($loot) : false;
            }));

            $looks = array_map(function ($item) {
                $weight = array_values(array_filter($item, function ($item) {
                    return strpos($item, 'weighs');
                }));

                $weight = (bool) count($weight) ? (int) filter_var($weight[0], FILTER_SANITIZE_NUMBER_INT) / 100 : 0;
                $name = trim(str_replace(['.'], '', explode('(', explode('You see', $item[0])[1])[0]));
                $amount = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);

                $name = $this->getItemName($name);
                $item = Item::where('name', 'like', "%{$name}%")->first();

                if (! $item) {
                    $this->error[] = $name;
                } else {
                    if (! $amount) {
                        $capacity = (int) $item->capacity;
                        $amount = $weight / $capacity;
                    }

                    $name = $amount > 0 ? "{$amount} {$name}" : $name;

                    return [
                        'loot'    => '',
                        'monster' => '',
                        'items'   => [
                            $name,
                        ],
                    ];
                }
            }, $items);
        } else {
            $looks = [];
        }

        return array_merge($loots, $looks);
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
			'death ring that is brand-new'							=> 'death ring',
			'ting ring that is brand-new'							=> 'time ring',
			'dwarven ring that is brand-new'						=> 'dwarven ring',
			'axe ring that is brand-new'							=> 'axe ring',
			'club ring that is brand-new'							=> 'club ring',
			'sword ring that is brand-new'							=> 'sword ring',
			'power ring that is brand-new'							=> 'power ring',
			'life ring that is brand-new'                     		=> 'life ring',
			'ring of healing that is brand-new'                     => 'ring of healing',
			'energy ring that is brand-new'                     	=> 'energy ring',
			'stealth ring that is brand-new'						=> 'stealth ring',
			'time ring that is brand-new'							=> 'time ring',
            'ring of green plasma that is brand-new'                => 'ring of green plasma',
            'ring of blue plasma that is brand-new'                 => 'ring of blue plasma',
            'ring of red plasma that is brand-new'                  => 'ring of red plasma',
            'collar of green plasma that is brand-new'              => 'collar of green plasma',
            'collar of blue plasma that is brand-new'               => 'collar of blue plasma',
            'collar of red plasma that is brand-new'                => 'collar of red plasma',
            'teeth file'					 => 'tooth file',
            'empty potion flasks'            => 'empty potion flask',
			'sparkion sting'				 => 'sparkion stings',
			'sparkion leg'					 => 'sparkion legs',
			'plasma pearl'					 => 'plasma pearls',
            'rainbow quartzes'               => 'rainbow quartz',
            'compasses'                      => 'compass',
            'lumps of dirt'                  => 'lump of dirt',
            'pieces of scarab shell'         => 'piece of scarab shell',
            'seed'                           => 'seeds',
            'bundles of cursed straw'        => 'bundle of cursed straw',
            'globs of acid slime'            => 'glob of acid slime',
            'globs of tar'                   => 'glob of tar',
            'pieces of hell steel'           => 'piece of hell steel',
            'bundles of cursed straws'       => 'bundle of cursed straw',
            'golden mug It is empty'         => 'golden mug',
            'strands of medusa hair'         => 'strand of medusa hair',
            'scrolls of heroic deeds'        => 'scroll of heroic deeds',
			'pieces of draconian steel'		 => 'piece of draconian steel',
            'pairs of hellflayer horns'      => 'pair of hellflayer horns',
			'demonic essences'				 => 'demonic essence',
			'pools of chitinous glue'		 => 'pool of chitinous glue',
		    'mystical hourglasses'			 => 'mystical hourglass',
            'bowls of terror sweat'			 => 'bowl of terror sweat',
		    'broken dreams'					 => 'broken dream',  
			'coral brooches'				 => 'coral brooch',
			'pieces of royal steel'			 => 'piece of royal steel',
			'mystical hourgla'				 => 'mystical hourglass',
			'clusters of solace'			 => 'cluster of solace',
			'moonlight crystal'				 => 'moonlight crystals',
			'gear wheels'					 => 'gear wheel',
			'bronze gear wheels'			 => 'bronze gear wheel',
            'gold coins'                     => 'gold coin',
            'platinum coins'                 => 'platinum coin',
            'crystal coins'                  => 'crystal coin',
            'small emeralds'                 => 'small emerald',
            'emerald bangles'                => 'emerald bangle',
            'scarab coins'                   => 'scarab coin',
            'small amethysts'                => 'small amethyst',
            'small rubies'                   => 'small ruby',
            'small diamonds'                 => 'small diamond',
            'white pearls'                   => 'white pearl',
            'black pearls'                   => 'black pearl',
            'silver broochs'                 => 'silver brooch',
            'silver brooches'				 => 'silver brooch',
            'small sapphires'                => 'small sapphire',
            'gold nuggets'                   => 'gold nugget',
            'orichalcum pearls'              => 'orichalcum pearl',
            'christmas tokens'               => 'christmas token',
            'giant shimmering pearls'        => 'giant shimmering pearl',
            'small enchanted sapphires'      => 'small enchanted sapphire',
            'small enchanted emeralds'       => 'small enchanted emerald',
            'small enchanted rubies'         => 'small enchanted ruby',
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
            'golden lotus brooches'          => 'golden lotus brooch',
            'moonlight crystals'             => 'moonlight crystal',
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
            'prismatic quartzes'              => 'prismatic quartz',
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
            "brown pieces of cloth"          => "brown piece of cloth",
            "green dragon leathers"          => "green dragon leather",
            "enchanted chicken wings"        => "enchanted chicken wing",
            "green dragon scales"            => "green dragon scale",
            "iron ores"                      => "iron ore",
            "minotaur leathers"              => "minotaur leather",
            "bonelord eyes"                  => "bonelord eye",
            "bear paws"                      => "bear paw",
            "wolf paws"                      => "wolf paw",
            "white pieces of cloth"          => "white piece of cloth",
            "red dragon scales"              => "red dragon scale",
            "demon horns"                    => "demon horn",
            "chicken feathers"               => "chicken feather",
            "woods"                          => "wood",
            "ape furs"                       => "ape fur",
            "red dragon leathers"            => "red dragon leather",
            "perfect behemoth fangs"         => "perfect behemoth fang",
            "turtle shells"                  => "turtle shell",
            "heaven blossoms"                => "heaven blossom",
            "yellow pieces of cloth"         => "yellow piece of cloth",
            "sniper gloves"                  => "sniper glove",
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
            "blue pieces of cloth"           => "blue piece of cloth",
            "green pieces of cloth"          => "green piece of cloth",
            "behemoth claws"                 => "behemoth claw",
            "red pieces of cloth"            => "red piece of cloth",
            "nose rings"                     => "nose ring",
            "soul stones"                    => "soul stone",
            "magic sulphurs"                 => "magic sulphur",
            "flask of warrior's sweats"      => "flask of warrior's sweat",
            "dwarven beards"                 => "dwarven beard",
            "Orshabaal's brains"             => "Orshabaal's brain",
            "Mr. Punish's handcuffs"         => "Mr. Punish's handcuff",
            "cat's paws"                     => "cat's paw",
            "Dracola's eyes"                 => "Dracola's eye",
            "the Imperor's tridents"         => "the Imperor's trident",
            "the Plasmother's remains"       => "the Plasmother's remain",
            "the Handmaiden's protectors"    => "the Handmaiden's protector",
            "Countess Sorrow's frozen tears" => "Countess Sorrow's frozen tear",
            "shards"                         => "shard",
            "Morgaroth's hearts"             => "Morgaroth's heart",
            "piece of Massacre's shells"     => "piece of Massacre's shell",
            "technomancer beards"            => "technomancer beard",
            "cockroach legs"                 => "cockroach leg",
            "orc tusks"                      => "orc tusk",
            "glands"                         => "gland",
            "glob of mercuries"              => "glob of mercury",
            "glob of acid slimes"            => "glob of acid slime",
            "glob of tars"                   => "glob of tar",
            "spider silks"                   => "spider silk",
            "egg of The Manies"              => "egg of The Many",
            "bunch of troll hairs"           => "bunch of troll hair",
            "lump of dirts"                  => "lump of dirt",
            "orc tooths"                     => "orc tooth",
            "shaggy tails"                   => "shaggy tail",
            "sandcrawler shells"             => "sandcrawler shell",
            "zaogun shoulderplates"          => "zaogun shoulderplates",
            "warwolf furs"                   => "warwolf fur",
            "bony tails"                     => "bony tail",
            "half-digested piece of meats"   => "half-digested piece of meat",
            "hellspawn tails"                => "hellspawn tail",
            "piece of crocodile leathers"    => "piece of crocodile leather",
            "mutated fleshs"                 => "mutated flesh",
            "essences of a bad dreams"       => "essence of a bad dream",
            "essences of a bad dream"		 => "essence of a bad dream",
            "tarantula eggs"                 => "tarantula egg",
            "lumps of earth"                 => "lump of earth",
            "centipede legs"                 => "centipede leg",
            "hydra heads"                    => "hydra head",
            "spiked iron balls"              => "spiked iron ball",
            "snake skins"                    => "snake skin",
            "bone shoulderplates"            => "bone shoulderplate",
            "sulphurous stones"              => "sulphurous stone",
            "boggy dreads"                   => "boggy dread",
            "dark rosaries"                  => "dark rosary",
            "rotten pieces of cloth"         => "rotten piece of cloth",
            "mammoth tusks"                  => "mammoth tusk",
            "hellhound slobbers"             => "hellhound slobber",
            "mutated bat ears"               => "mutated bat ear",
            "broken gladiator shields"       => "broken gladiator shield",
            "carniphila seeds"               => "carniphila seed",
            "carrion worm fangs"             => "carrion worm fang",
            "bundles of crused straw"		 => "bundle of cursed straw",
            "cobra tongues"                  => "cobra tongue",
            "compass"                        => "compa",
            "weaver's wandtips"              => "weaver's wandtip",
            "badger furs"                    => "badger fur",
            "acorns"                         => "acorn",
            "ancient stones"                 => "ancient stone",
            "black hoods"                    => "black hood",
            "book of prayers"                => "book of prayer",
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
            //"mystical hourglass"             => "mystical hourgla",
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
            "swamp grass"                    => "swamp gra",
            "striped furs"                   => "striped fur",
            "strand of medusa hairs"         => "strand of medusa hair",
            "stone wings"                    => "stone wing",
            "silky furs"                     => "silky fur",
            "skunk tails"                    => "skunk tail",
            "shiny stones"                   => "shiny stone",
            "war crystals"                   => "war crystal",
            "werewolf furs"                  => "werewolf fur",
            "widow's mandibles"              => "widow's mandible",
            "winged tails"                   => "winged tail",
            "winter wolf furs"               => "winter wolf fur",
            "witch brooms"                   => "witch broom",
            "wools"                          => "wool",
            "wyrm scales"                    => "wyrm scale",
            "wyvern talismans"               => "wyvern talisman",
            "lancer beetle shells"           => "lancer beetle shell",
            "red lanterns"                   => "red lantern",
            "antlers"                        => "antler",
            "cyclops toes"                   => "cyclops toe",
            "frosty ear of a trolls"         => "frosty ear of a troll",
            "sabreteeth"                     => "sabretooth",
            "sabretooths"                    => "sabretooth",
            "terramite legs"                 => "terramite leg",
            "terramite shells"               => "terramite shell",
            "terrorbird beaks"               => "terrorbird beak",
            "broken halberds"                => "broken halberd",
            "cursed shoulder spike"         => "cursed shoulder spikes",
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
            "elven scouting glass"           => "elven scouting gla",
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
            "quara pincers"                  => "quara pincer",
            "quara tentacles"                => "quara tentacle",
            "rope belts"                     => "rope belt",
            "safety pins"                    => "safety pin",
            "shamanic hoods"                 => "shamanic hood",
            "small flask of eyedrops"        => "small flask of eyedrop",
            "small pitchforks"               => "small pitchfork",
            "trollroots"                     => "trollroot",
            "brimstone shells"               => "brimstone shell",
            "draken sulphurs"                => "draken sulphur",
            "eyes of corruption"             => "eye of corruption",
            "lizard essences"                => "lizard essence",
            "scales of corruption"           => "scale of corruption",
            "tails of corruption"            => "tail of corruption",
            "tentacle pieces"                => "tentacle piece",
            "skull belts"                    => "skull belt",
            "draken wristbands"              => "draken wristband",
            "broken slicers"                 => "broken slicer",
            "broken draken mails"            => "broken draken mail",
            "panther paws"                   => "panther paw",
            "stampor horns"                  => "stampor horn",
            "stampor talons"                 => "stampor talon",
            "hollow stampor hoofs"           => "hollow stampor hoof",
            "draptor scales"                 => "draptor scale",
            "panther heads"                  => "panther head",
            "maxillas"                       => "maxilla",
            "giant crab pincers"             => "giant crab pincer",
            "cavebear skulls"                => "cavebear skull",
            "eye of a deeplings"             => "eye of a deepling",
            "slime moulds"                   => "slime mould",
            "yielocks"                       => "yielock",
            "yielowaxs"                      => "yielowax",
            "white deer skins"               => "white deer skin",
            "white deer antlers"             => "white deer antler",
            "flintstones"                    => "flintstone",
            "demonic fingers"                => "demonic finger",
            "coals"                          => "coal",
            "broken rings of ending"         => "broken ring of ending",
            "ominous pieces of cloth"        => "ominous piece of cloth",
            "ludicrous pieces of cloth"      => "ludicrous piece of cloth",
            "voluminous pieces of cloth"     => "voluminous piece of cloth",
            "luminous pieces of cloth"       => "luminous piece of cloth",
            "obvious pieces of cloth"        => "obvious piece of cloth",
            "dubious pieces of cloth"        => "dubious piece of cloth",
            "elemental spikes"               => "elemental spike",
            "deepling claws"                 => "deepling claw",
            "crawler head platings"          => "crawler head plating",
            "deepling breaktime snacks"      => "deepling breaktime snack",
            "deepling guard belt buckles"    => "deepling guard belt buckle",
            "deepling ridges"                => "deepling ridge",
            "deepling scales"                => "deepling scale",
            "deepling warts"                 => "deepling wart",
            "compound eyes"                  => "compound eye",
            "spidris mandibles"              => "spidris mandible",
            "spitter noses"                  => "spitter nose",
            "swarmer antennas"               => "swarmer antenna",
            "waspoid claws"                  => "waspoid claw",
            "waspoid wings"                  => "waspoid wing",
            "deeptags"                       => "deeptag",
            "dung balls"                     => "dung ball",
            "key to the Drowned Libraries"   => "key to the Drowned Library",
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
            "veins of ore"                   => "vein of ore",
            "stone noses"                    => "stone nose",
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
            "lost bracers"                   => "lost bracer",
            "mad froths"                     => "mad froth",
            "red hair dyes"                  => "red hair dye",
            "skull shatterers"               => "skull shatterer",
            "swampling moss"                 => "swampling mo",
            "wimp tooth chains"              => "wimp tooth chain",
            "bone fetishs"                   => "bone fetish",
            "bonecarving knifes"             => "bonecarving knife",
            "bloody dwarven beards"          => "bloody dwarven beard",
            "broken throwing axes"           => "broken throwing axe",
            "tooth files"                    => "tooth file",
            "lancets"                        => "lancet",
            "horoscopes"                     => "horoscope",
            "incantation note"              => "incantation notes",
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
            "werebadger skulls"              => "werebadger skull",
            "werebear skulls"                => "werebear skull",
            "werebear furs"                  => "werebear fur",
            "ogre ear studs"                 => "ogre ear stud",
            "ogre nose rings"                => "ogre nose ring",
            "shamanic talismans"             => "shamanic talisman",
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
            "curious matters"                => "curious matter",
            "volatile proto matters"         => "volatile proto matter",
            "odd organs"                     => "odd organ",
            "energy balls"                   => "energy ball",
            "condensed energies"             => "condensed energy",
            "crystal bones"                  => "crystal bone",
            "small energy balls"             => "small energy ball",
            "strange proto matters"          => "strange proto matter",
            "dangerous proto matters"        => "dangerous proto matter",
            "glistening bones"               => "glistening bone",
            "coal eyes"                      => "coal eye",
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
            "blueberries"                    => "blueberry",
            "cheeses"                        => "cheese",
            "fishs"                          => "fish",
            "hams"                           => "ham",
            "meats"                          => "meat",
            "red apples"                     => "red apple",
            "carrots"                        => "carrot",
            "coconuts"                       => "coconut",
            "bananas"                        => "banana",
            "oranges"                        => "orange",
            "white mushrooms"                => "white mushroom",
            "eggs"                           => "egg",
            "breads"                         => "bread",
            "cherries"                       => "cherry",
            "brown breads"                   => "brown bread",
            "brown mushrooms"                => "brown mushroom",
            "candy canes"                    => "candy cane",
            "cookies"                        => "cookie",
            "corncobs"                       => "corncob",
            "dark mushrooms"                 => "dark mushroom",
            "dragon hams"                    => "dragon ham",
            "fire mushrooms"                 => "fire mushroom",
            "green mushrooms"                => "green mushroom",
            "salmons"                        => "salmon",
            "melons"                         => "melon",
            "orange mushrooms"               => "orange mushroom",
            "pears"                          => "pear",
            "shrimps"                        => "shrimp",
            "red mushrooms"                  => "red mushroom",
            "rolls"                          => "roll",
            "strawberries"                   => "strawberry",
            "tomatos"                        => "tomato",
            "wood mushrooms"                 => "wood mushroom",
            "pumpkins"                       => "pumpkin",
            "flours"                         => "flour",
            "lump of doughs"                 => "lump of dough",
            "tortoise eggs"                  => "tortoise egg",
            "mangos"                         => "mango",
            "some mushrooms"                 => "some mushroom",
            "some mushrooms"                 => "some mushroom",
            "gingerbreadmans"                => "gingerbreadman",
            "lump of cake doughs"            => "lump of cake dough",
            "cakes"                          => "cake",
            "cream cakes"                    => "cream cake",
            "valentine's cakes"              => "valentine's cake",
            "cakes"                          => "cake",
            "bar of chocolates"              => "bar of chocolate",
            "candies"                        => "candy",
            "the carrot of dooms"            => "the carrot of doom",
            "coloured eggs"                  => "coloured egg",
            "coloured eggs"                  => "coloured egg",
            "coloured eggs"                  => "coloured egg",
            "coloured eggs"                  => "coloured egg",
            "coloured eggs"                  => "coloured egg",
            "rainbow trouts"                 => "rainbow trout",
            "northern pikes"                 => "northern pike",
            "green perchs"                   => "green perch",
            "scarab cheeses"                 => "scarab cheese",
            "walnuts"                        => "walnut",
            "peanuts"                        => "peanut",
            "ice cream cones"                => "ice cream cone",
            "marlins"                        => "marlin",
            "ice cream cones"                => "ice cream cone",
            "ice cream cones"                => "ice cream cone",
            "ice cream cones"                => "ice cream cone",
            "raspberries"                    => "raspberry",
            "lemons"                         => "lemon",
            "plums"                          => "plum",
            "cucumbers"                      => "cucumber",
            "beetroots"                      => "beetroot",
            "onions"                         => "onion",
            "jalapeño peppers"               => "jalapeño pepper",
            "chocolate cakes"                => "chocolate cake",
            "lump of chocolate doughs"       => "lump of chocolate dough",
            "baking traies"                  => "baking tray",
            "tortoise egg from nargors"      => "tortoise egg from nargor",
            "yummy gummy worms"              => "yummy gummy worm",
            "ice cream cones"                => "ice cream cone",
            "bulb of garlics"                => "bulb of garlic",
            "blessed steaks"                 => "blessed steak",
            "veggie casseroles"              => "veggie casserole",
            "filled jalapeño peppers"        => "filled jalapeño pepper",
            "tropical fried terrorbirds"     => "tropical fried terrorbird",
            "roasted dragon wings"           => "roasted dragon wing",
            "northern fishburgers"           => "northern fishburger",
            "rotworm stews"                  => "rotworm stew",
            "carrot cakes"                   => "carrot cake",
            "hydra tongue salads"            => "hydra tongue salad",
            "potatos"                        => "potato",
            "rice balls"                     => "rice ball",
            "terramite eggs"                 => "terramite egg",
            "crocodile steaks"               => "crocodile steak",
            "hydra meats"                    => "hydra meat",
            "aubergines"                     => "aubergine",
            "broccolis"                      => "broccoli",
            "cauliflowers"                   => "cauliflower",
            "dragonfruits"                   => "dragonfruit",
            "lettuces"                       => "lettuce",
            "peas"                           => "pea",
            "pineapples"                     => "pineapple",
            "starfruits"                     => "starfruit",
            "coconut shrimp bakes"           => "coconut shrimp bake",
            "pot of blackjacks"              => "pot of blackjack",
            "ectoplasmic sushis"             => "ectoplasmic sushi",
            "demonic candy balls"            => "demonic candy ball",
            "haunch of boars"                => "haunch of boar",
            "deepling filets"                => "deepling filet",
            "larvaes"                        => "larvae",
            "sandfishs"                      => "sandfish",
            "ice cream cones"                => "ice cream cone",
            "ice cream cones"                => "ice cream cone",
            "anniversary cakes"              => "anniversary cake",
            "cheese cookies"                 => "cheese cookie",
            "mushroom pies"                  => "mushroom pie",
            "insectoid eggs"                 => "insectoid egg",
            "rat cheeses"                    => "rat cheese",
            "soft cheeses"                   => "soft cheese",
            "christmas cookie traies"        => "christmas cookie tray",
            "glooth sandwichs"               => "glooth sandwich",
            "bottle of glooth wines"         => "bottle of glooth wine",
            "bowl of glooth soups"           => "bowl of glooth soup",
            "glooth steaks"                  => "glooth steak",
            "raw meats"                      => "Raw Meat",
            "roasted meats"                  => "roasted meat",
            "prickly pears"                  => "prickly pear",
            "energy drinks"                  => "energy drink",
            "energy bars"                    => "energy bar",
            "forbidden fruits"               => "forbidden fruit",
            "cave turnips"                   => "cave turnip",
            "talons"                         => "talon",
            "life crystals"                  => "life crystal",
            "soul orbs"                      => "soul orb",
            "viper stars"                    => "viper star",
            "throwing stars"                 => "throwing star",
            "throwing knives"                => "throwing knife",
            "bunches of ripe rice"           => "bunch of ripe rice",
            "peacock feather fans"           => "peacock feather fan",
            "lost husher's staff"            => "Lost Husher's Staff",
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
