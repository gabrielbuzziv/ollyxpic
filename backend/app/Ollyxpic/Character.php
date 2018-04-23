<?php

namespace App\Ollyxpic;

use App\Ollyxpic\Crawlers\CharacterCrawler;
use App\Ollyxpic\TibiaData\CharacterAPI;

class Character
{
    /**
     * Get the best crawler option.
     *
     * @param $name
     * @return array|bool
     */
    public function check($name)
    {
        if (($character = (new CharacterAPI($name))->get()) !== []) {
            return $character;
        }

        return (new CharacterCrawler($name))->run();
    }
}