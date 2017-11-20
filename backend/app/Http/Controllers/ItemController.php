<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\ItemBuy;
use App\ItemProperties;
use App\ItemSell;
use App\NPC;
use App\WikiItems;
use App\WorldObject;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    /**
     * Properties types.
     *
     * @var array
     */
    protected $properties = [
        'protection physical',
        'fire',
        'energy',
        'ice',
        'earth',
        'death',
        'speed',
        'sword fighting',
        'axe fighting',
        'club fighting',
        'distance fighting',
        'shielding',
        'magic level',
        'arm',
        'def',
        'atk',
        'life drain',
        'mana drain',
        'hit'
    ];

    /**
     * Get items from database.db
     * If items existing in the mysql db update data, if not create them.
     *
     * @return $this
     */
    public function syncronize()
    {
        $drops = WikiCreatureDrop::with('creature', 'item');
        $drops->each(function ($drop) {
            $item = Item::where('title', $drop->item->title)->where('name', $drop->item->name)->first();
            $creature = Item::where('title', $drop->creature->title)->where('name', $drop->creature->name)->first();

            $data = CreatureDrop::firstOrNew(['creature_id' => $creature->id, 'item_id' => $item->id]);
            $data->percentage = $drop->percentage;
            $data->min = $drop->min;
            $data->max = $drop->max;
            $data->save();
        });


        $items = WikiItems::with('properties', 'sells.npc', 'buys')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->get();

        return $items->each(function ($item) {
            $data = Item::firstOrNew(['title' => $item->title, 'name' => $item->name]);
            $data->vendor_value = $item->vendor_value;
            $data->actual_value = $item->actual_value;
            $data->capacity = $item->capacity;
            $data->stackable = $item->stackable;
            $data->image = $item->image;
            $data->category_id = Category::where('name', str_slug($item->category))->first()->id;
            $data->discard = $item->discard;
            $data->convert_to_gold = $item->convert_to_gold;
            $data->look_text = $item->look_text;
            $data->save();

            $defaultProperties = $this->getDefaultProps($item);
            $levelProperties = $this->getLevelProps($item);
            $properties = array_merge($defaultProperties, $levelProperties);

            foreach ($properties as $property) {
                $itemProperty = ItemProperties::firstOrNew(['item_id' => $data->id, 'property' => $property['property']]);
                $itemProperty->value = $property['value'];
                $itemProperty->save();
            }

            foreach ($item->sells as $sell) {
                $npc = NPC::where('name', $sell->npc->title)->first();
                $itemSell = ItemSell::firstOrNew(['item_id' => $data->id, 'npc_id' => $npc->id]);
                $itemSell->value = $sell->value;
                $itemSell->save();
            }

            foreach ($item->buys as $buy) {
                $npc = NPC::where('name', $buy->npc->title)->first();
                $itemBuy = ItemBuy::firstOrNew(['item_id' => $data->id, 'npc_id' => $npc->id]);
                $itemBuy->value = $buy->value;
                $itemBuy->save();
            }

            return $data;
        });
    }

    /**
     * Get all items from category.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        $items = $category->items()->with('category', 'properties', 'sells.npc', 'buys.npc')->get();

        return $this->respond($items->toArray());
    }

    /**
     * Show item.
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Item $item)
    {
        return $this->respond($item->toArray());
    }

    /**
     * Delete items
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return $this->respond(['removed' => true]);
    }

    /**
     * Get all items from category.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function usables(Category $category)
    {
        $items = $category->items()->with('properties')->usable()->get();

        return $this->respond($items->toArray());
    }

    /**
     * Toggle Usable.
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleUsable(Item $item)
    {
        $item->usable = $item->usable ? 0 : 1;
        $item->save();

        return $this->respond($item->toArray());
    }

    /**
     * Get default props.
     *
     * @param $item
     * @return array
     */
    private function getDefaultProps($item)
    {
        $default = [];
        $text = explode('.', $item->look_text);
        $properties = explode(', ', $this->between('(', ')', $text[0]));
        foreach ($properties as $property) {
            if ($this->isValidProperty($property)) {
                $default[] = [
                    'property' => $this->getProperty($property)->description,
                    'value'    => $this->getProperty($property)->value
                ];
            }
        }

        return $default;
    }

    /**
     * Get level prop.
     *
     * @param $item
     * @return array
     */
    public function getLevelProps($item)
    {
        $props = [];
        $text = explode('.', $item->look_text);
        if (strpos($text[1], 'level') !== false) {
            preg_match('/level\s*(\d+)/', $item->look_text, $matches);
            if (count($matches) == 0) {
                dd($item->look_text);
            }
            $props[] = [
                'property' => 'level',
                'value'    => (int) filter_var($matches[0], FILTER_SANITIZE_NUMBER_INT)
            ];
        }

        return $props;
    }

    /**
     * Is Valid Property
     *
     * @param $property
     * @return bool|mixed
     */
    private function isValidProperty($property)
    {
        foreach ($this->properties as $type) {
            if (strpos(str_replace(':', '', strtolower($property)), $type) !== false) {
                return $type;
            }
        }

        return false;
    }

    /**
     * Get property type.
     *
     * @param $property
     * @return mixed
     */
    private function getProperty($property)
    {
        $prop = [
            'description' => '',
            'value'       => ''
        ];
        $prop['description'] = $this->isValidProperty($property);
        $value = (int) filter_var($property, FILTER_SANITIZE_NUMBER_INT);
        if (substr($property, - 1) == '%') {
            $prop['value'] = $value / 100;
        } else {
            $prop['value'] = $value;
        }

        return (object) $prop;
    }

    /**
     * Get text between 2 strings.
     *
     * @param $before
     * @param $after
     * @param $string
     * @return string
     */
    private function between($before, $after, $string)
    {
        $temp1 = strpos($string, $before) + strlen($before);
        $result = substr($string, $temp1, strlen($string));
        $dd = strpos($result, $after);
        if ($dd == 0) {
            $dd = strlen($result);
        }

        return substr($result, 0, $dd);
    }

}
