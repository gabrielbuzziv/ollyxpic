<?php

namespace App\Http\Controllers;

use App\Items;
use Illuminate\Http\Request;

class ItemsController extends Controller
{

    /**
     * Return items by request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = Items::all();

        return $this->respond($items->toArray());
    }

    public function potions()
    {
        $potions = [
            1025, // Mana Potion
            1018, // Strong Mana Potion
            1019, // Great Mana Potion
            3147, // Ultimate Mana Potion
            1168, // Small Health Potion
            1037, // Health Potion
            1020, // Strong Health Potion
            1021, // Great Health Potion
            1113, // Ultimate Health Potion
            3146, // Supreme Health Potion
            1112, // Great Spirit Potion
            3148, // Ultimate Spirit Potion
            917, // Berserker Potion
            918, // Bulleyes Potion
            943, // Mastermind Potion
        ];

        $order = implode(',', $potions);

        $items = Items::whereIn('id', $potions)
            ->orderByRaw("
                CASE id
                    WHEN 1025 THEN 0
                    WHEN 1018 THEN 1
                    WHEN 1019 THEN 2
                    WHEN 3147 THEN 3
                    WHEN 1168 THEN 4
                    WHEN 1037 THEN 5
                    WHEN 1020 THEN 6
                    WHEN 1021 THEN 7
                    WHEN 1113 THEN 8
                    WHEN 3146 THEN 9
                    WHEN 1112 THEN 10
                    WHEN 3148 THEN 11
                    WHEN 917 THEN 12
                    WHEN 918 THEN 13
                    WHEN 943 THEN 14
                END
            ")
            ->get();

        return $this->respond($items->toArray());
    }

    public function ammunitions()
    {
        $ammunition = [
            61, // Spear
            449, // Hunting Spear
            951, // Royal Spear
            922, // Enchanted Spear
            257, // Throwing Star
            965, // Viper Star
            919, // Assassin Star
            60, // Arrow
            89, // Poison Arrow
            91, // Burst Arrow
            925, // Sniper Arrow
            927, // Onyx Arrow
            1027, // Earth Arrow
            1028, // Flaming Arrow
            1029, // Shiver Arrow
            1030, // Flash Arrow
            2218, // Tarsal Arrow
            2377, // Envenomed Arrow
            2391, // Crystalline Arrow
            66, // Bolt
            261, // Power Bolt
            811, // Infernal Bolt
            923, // Piercing Bolt
            2224, // Vortex Bolt
            2375, // Prismatic Bolt
            2376, // Drill Bolt
        ];

        $order = implode(',', $ammunition);

        $items = Items::whereIn('id', $ammunition)
            ->orderByRaw("
                CASE id
                    WHEN 61 THEN 0
                    WHEN 449 THEN 1
                    WHEN 951 THEN 2
                    WHEN 922 THEN 3
                    WHEN 257 THEN 4
                    WHEN 965 THEN 5
                    WHEN 919 THEN 6
                    WHEN 60 THEN 7
                    WHEN 89 THEN 8
                    WHEN 91 THEN 9
                    WHEN 925 THEN 10
                    WHEN 927 THEN 11
                    WHEN 1027 THEN 12
                    WHEN 1028 THEN 13
                    WHEN 1029 THEN 14
                    WHEN 1030 THEN 15
                    WHEN 2218 THEN 16
                    WHEN 2377 THEN 17
                    WHEN 2391 THEN 18
                    WHEN 66 THEN 19
                    WHEN 261 THEN 20
                    WHEN 811 THEN 21
                    WHEN 923 THEN 22
                    WHEN 2224 THEN 23
                    WHEN 2375 THEN 24
                    WHEN 2376 THEN 25
                END
            ")
            ->get();

        return $this->respond($items->toArray());
    }

    public function runes()
    {
        $runes = [
            117, // Sudden Death Rune
            2024, // Avalanche Rune
            2010, // Great Fireball Rune
            2017, // Thunderstorm Rune
            2022, // Stone Shower Rune
            1109, // Fire Wall Rune,
            1108, // Energy Wall Rune
            1110, // Poison Wall Rune
            1111, // Energy Bomb Rune
            2008, // Fire Bomb Rune
            2021, // Poison Bomb Rune
            2009, // Fireball Rune
            2012, // Light Magic Missile Rune
            2011, // Heavy Magic Missile Rune
            2018, // Holy Missile Rune
            2019, // Icicle Rune
            1305, // Explosion Rune
            2016, // Stalagmite Rune
            1103, // Fire Field Rune
            2007, // Energy Field Rune
            2014, // Poison Field Rune
            2006, // Destroy Field Rune
            2013, // Magic Wall Rune
            2023, // Wild Growth Rune
            2015, // Soulfire Rune
            2005, // Disintegrate Rune
            2020, // Paralyse Rune
            2025, // Chameleon Rune
            2026, // Convince Creature Rune
            2433, // Intense Healing Rune (Item)
            2434, // Ultimate Healing Rune (Item)
        ];

        $order = implode(',', $runes);

        $items = Items::whereIn('id', $runes)
            ->orderByRaw("
                CASE id
                    WHEN 117 THEN 0
                    WHEN 2024 THEN 1
                    WHEN 2010 THEN 2
                    WHEN 2017 THEN 3
                    WHEN 2022 THEN 4
                    WHEN 1109 THEN 5
                    WHEN 1108 THEN 6
                    WHEN 1110 THEN 7
                    WHEN 1111 THEN 8
                    WHEN 2008 THEN 9
                    WHEN 2021 THEN 10
                    WHEN 2009 THEN 11
                    WHEN 2012 THEN 12
                    WHEN 2011 THEN 13
                    WHEN 2018 THEN 14
                    WHEN 2019 THEN 15
                    WHEN 1305 THEN 16
                    WHEN 2016 THEN 17
                    WHEN 1103 THEN 18
                    WHEN 2007 THEN 19
                    WHEN 2014 THEN 20
                    WHEN 2006 THEN 21
                    WHEN 2013 THEN 22
                    WHEN 2023 THEN 23
                    WHEN 2015 THEN 24
                    WHEN 2005 THEN 25
                    WHEN 2020 THEN 26
                    WHEN 2025 THEN 27
                    WHEN 2026 THEN 28
                    WHEN 2433 THEN 29
                    WHEN 2434 THEN 30
                END
            ")
            ->get();

        return $this->respond($items->toArray());
    }

    public function amulets()
    {
        $amulets = [
            169, // Amulet of Loss
            177, // Protection Amulet
            178, // Stone Skin Amulet
            180, // Bronze Amulet
            1008, // Terra Amulet
            1141, // Glacier Amulet
            1362, // Magma Amulet
            1499, // Shockwave Amulet
            1518, //
            2784, //
            174, // Garlic Necklace,
            2342, // Gill Necklace
            2347, // Prismatic Necklace
        ];

        $order = implode(',', $amulets);

        $items = Items::whereIn('id', $amulets)
            ->get();

        return $this->respond($items->toArray());
    }

    public function rings()
    {
        $rings = [
            84, // Time Ring
            99, // Dwarven Ring
            100, // Axe Ring
            101, // Club Ring
            102, // Sword Ring
            103, // Power Ring
            104, // Life Ring
            105, // Ring of Realing
            107, // Energy Ring
            109, // Stealth Ring
            110, // Might Ring
            818, // Death Ring
            2348, // Prismatic Ring
            3216, // Ring of Red Plasma
            3217, // Ring of Green Plasma
            3218, // Ring of Blue Plasma
        ];

        $order = implode(',', $rings);

        $items = Items::whereIn('id', $rings)
            ->get();

        return $this->respond($items->toArray());
    }
}
