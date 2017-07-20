<?php

namespace App\Http\Controllers;

use App\Category;
use App\ItemProps;
use App\Items;
use Illuminate\Http\Request;

class ItemPropsController extends Controller
{

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
     * Set properties.
     *
     * @return array
     */
    public function setProperties()
    {
        $items = Category::get();

        foreach ($items as $item) {
            $defaultProps = $this->getDefaultProps($item->item);
            $levelProps = $this->getLevelProps($item->item);

            $props = array_merge($defaultProps, $levelProps);

            foreach ($props as $prop) {
                $item->item->props()->create($prop);
            }
        }
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
                    'description' => $this->getProperty($property)->description,
                    'value'       => $this->getProperty($property)->value
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
                'description' => 'level',
                'value' => (int) filter_var($matches[0], FILTER_SANITIZE_NUMBER_INT)
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
            'value' => ''
        ];

        $prop['description'] = $this->isValidProperty($property);
        $value = (int) filter_var($property, FILTER_SANITIZE_NUMBER_INT);

        if (substr($property, -1) == '%') {
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
