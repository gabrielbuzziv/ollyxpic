<?php

namespace App\Http\Controllers;

use App\Helper\GifCreator;
use App\Helper\Helper;
use App\HuntItems;
use App\Hunts;
use App\HuntTeammates;
use App\Item;
use \App\Helper\GifFrameExtractor;
use App\Translation;
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

        foreach ($this->error as $error) {
            Translation::create(['from' => $error, 'to' => $error, 'fixed' => 0]);
        }

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
        if ( ! $this->filters->goldcoins) {
            $items = array_filter($items, function ($item) {
                return $item['data']['name'] != 'gold coin';
            });
        }

        if ($this->filters->effective) {
            $items = array_filter($items, function ($item) {
                return ($item['data']['vendor_value'] / $item['data']['capacity']) >= 10;
            });
        }

        if ( ! $this->filters->stackable) {
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

                    if ( ! Item::where('name', $name)->first()) {
                        $this->error[] = $name;

                    } else {
                        if ( ! isset($items[$name])) {
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

                $weight = count($weight) ? (float) trim(str_replace(['It weighs', 'oz.'], '', $weight[0])) : 0;
                $name = trim(str_replace(['.'], '', explode('(', explode('You see', $item[0])[1])[0]));
                $amount = (int) filter_var($name, FILTER_SANITIZE_NUMBER_INT);

                $name = $this->getItemName($name);
                $item = Item::where('name', 'like', "%{$name}%")->first();

                if ( ! $item) {
                    $this->error[] = $name;
                } else {
                    if ( ! $amount) {
                        $amount = (int) ($weight / $item->capacity);
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
            '(increased loot)',
            '(active prey bonus)',
            '(common)',
            '(semi-rare)',
            '(rare)',
        ], '', $name));

        if ($translation = Translation::where('from', $name)->first()) {
            return $translation->to;
        }

        return $name;
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
