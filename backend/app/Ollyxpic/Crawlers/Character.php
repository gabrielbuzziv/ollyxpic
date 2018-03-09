<?php

namespace App\Ollyxpic\Crawlers;

use Carbon\Carbon;
use Goutte\Client;

class Character
{

    /**
     * Name
     *
     * @var
     */
    protected $name;

    /**
     * Details
     *
     * @var
     */
    protected $details;

    /**
     * Deaths
     *
     * @var
     */
    protected $deaths;

    /**
     * Character constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->details = [];
        $this->deaths = [];
    }

    /**
     * Run the crawler.
     *
     * @return array
     */
    public function run()
    {
        $this->getCharacterDetails();
        $this->getCharacterDeaths();

        return [
            'details' => $this->details,
            'deaths'  => $this->deaths
        ];
    }

    /**
     * Get character death and add in $deaths attribute.
     */
    private function getCharacterDeaths()
    {
        $table = $this->getCharacterBox('deaths');

        $this->deaths = $table !== false
            ? array_values(array_filter($table->filter('tr')->each(function ($tr, $i) {
                if ($i == 0) return;

                $death = $tr->filter('td')->each(function ($td) {
                    return $td->text();
                });

                return $this->formatDeath($death);
            }), function ($death) {
                return ! empty($death);
            }))
            : [];
    }

    /**
     * Format Death.
     *
     * @param $death
     * @return array
     */
    private function formatDeath($death)
    {
        $date = Carbon::createFromFormat('M d Y, H:i:s T', $this->clearString($death[0]));
        $info = $this->clearString($death[1]);
        $level = $this->getDeathLevel($info);
        $reason = $this->getDeathReason($info);
        $assisted = $this->getDeathAssists($info);
        $involved = $this->getDeathInvolved($info);
        $type = $this->getDeathType($info);

        $join = ! empty($reason) && ! empty($assisted) ? '\r\n' : '';

        return [
            'date'     => $date,
            'level'    => $level,
            'reason'   => "{$reason}{$join}{$assisted}",
            'involved' => $involved,
            'type'     => $type
        ];
    }

    /**
     * Get death level.
     *
     * @param $death
     * @return int
     */
    private function getDeathLevel($death)
    {
        $offset = strlen("{$this->getDeathPrefix($death)} at Level ");
        $level = explode(' ', substr($death, $offset));

        return (int) $level[0];
    }

    /**
     * Get death reason.
     *
     * @param $death
     * @return string
     */
    private function getDeathReason($death)
    {
        $prefix = $this->getDeathPrefix($death);

        if ($prefix != 'Assisted') {
            $level = $this->getDeathLevel($death);
            $offset = strlen("{$prefix} at Level {$level} ");
            $reason = explode('.', $death);
            $reason = substr($reason[0], $offset);

            return "{$prefix} {$reason}.";
        }

        return null;
    }

    /**
     * Get death assists.
     *
     * @param $death
     * @return bool|null|string
     */
    private function getDeathAssists($death)
    {
        $offset = 'Assisted by ';
        if (strpos($death, $offset) === false) return null;

        $offset = strpos($death, $offset);
        $assisted = substr($death, $offset);

        return $this->clearString($assisted);
    }

    /**
     * Create a list of reason of death.
     *
     * @param $reason
     * @return array|bool|mixed|string
     */
    private function getReasonList($reason)
    {
        $prefix = $this->getDeathPrefix($reason);
        $offset = strlen("{$prefix} by ");
        $list = substr($reason, $offset);
        $list = str_replace([' and ', '.'], [', ', ''], $list);
        $list = explode(', ', $list);

        return $list;
    }

    /**
     * Get Involved in Death.
     *
     * @param $death
     * @return array
     */
    private function getDeathInvolved($death)
    {
        if ($this->getDeathType($death) != 'pvp' && $this->getDeathAssists($death) == null) return [];
        if ($this->getDeathType($death) == 'arena') return [];

        // Involed in Reason
        $reason = $this->getDeathReason($death);

        $prefix = $this->getDeathPrefix($reason);
        $offset = strlen("{$prefix} by ");
        $involvedReason = substr($reason, $offset);

        $involvedReason = str_replace([' and ', '.'], [', ', ''], $involvedReason);
        $involvedReason = explode(', ', $involvedReason);

        $involvedReason = array_filter($involvedReason, function ($involved) {
            return substr($involved, 0, 2) != 'a ' && ! empty($involved);
        });

        // Involved in Assist
        $assisted = $this->getDeathAssists($death);

        $prefix = $this->getDeathPrefix($assisted);
        $offset = strlen("{$prefix} by ");
        $involvedAssisted = substr($assisted, $offset);
        $involvedAssisted = str_replace([' and ', '.'], [', ', ''], $involvedAssisted);
        $involvedAssisted = explode(', ', $involvedAssisted);

        $involvedAssisted = array_filter($involvedAssisted, function ($involved) {
            return substr($involved, 0, 2) != 'a ' && ! empty($involved);
        });

        return array_merge($involvedReason, $involvedAssisted);
    }

    /**
     * Get death type.
     *
     * @param $death
     * @return string
     */
    private function getDeathType($death)
    {
        $prefix = strtolower($this->getDeathPrefix($death));

        return $prefix != 'died'
            ? 'pvp'
            : $this->identifyArenaDeaths($death)
                ? 'arena'
                : 'pve';
    }

    /**
     * Identify if the death was at arena.
     *
     * @param $death
     * @return bool
     */
    private function identifyArenaDeaths($death)
    {
        $reason = $this->getReasonList($this->getDeathReason($death));
        $arenaReasons = ['earth', 'energy', 'death', 'a pit reaver'];

        return count($reason) == 1 ? in_array($reason[0], $arenaReasons) : false;
    }

    /**
     * Get death prefix.
     *
     * @param $death
     * @return mixed
     */
    private function getDeathPrefix($death)
    {
        $death = explode(' ', $death);

        return $death[0];
    }

    /**
     * Get character details.
     */
    private function getCharacterDetails()
    {


        $this->getCharacterBox('detail')->filter('tr')->each(function ($tr, $i) {
            if ($i == 0) return;

            $details = $tr->filter('td')->each(function ($td) {
                return $td->text();
            });

            $label = $this->formatLabel($details[0]);
            $value = $this->formatDetails($label, $details[1]);
            $this->details[$label] = $value;
        });
    }

    /**
     * Format details values.
     *
     * @param $label
     * @param $value
     * @return int|string
     */
    private function formatDetails($label, $value)
    {
        $value = trim($value);

        switch ($label) {
            case 'level':
            case 'achievement_points':
                return (int) $value;
            case 'house':
                return $this->formatHouse($value);
            case 'guild':
                return $this->formatGuild($value);
            case 'last_login':
                return Carbon::createFromFormat('M d Y, H:i:s T', $this->clearString($value));
            case 'comment':
                return $value;
            default:
                return $this->clearString($value);
        }
    }

    /**
     * Format house as array.
     *
     * @param $house
     * @return array
     */
    private function formatHouse($house)
    {
        $house = $this->clearString($house);

        $sufix = strpos($house, '(Shop)') ? ' (Shop)' : '';
        $houseName = strpos($house, '(Shop)')
            ? explode('(Shop)', $house)
            : explode('(', $house);

        $town = explode(' ', trim($houseName[1]));
        $paid = strpos(trim($houseName[1]), 'until');
        $paid = substr(trim($houseName[1]), $paid + 5);
        $paid = Carbon::createFromFormat('M d Y', $this->clearString($paid))->format('Y-m-d');

        $town = str_replace(['(', ')'], '', $town[0]);
        $houseName = trim($houseName[0]);

        return [
            'house' => "{$houseName}{$sufix}",
            'town'  => $town,
            'paid'  => $paid
        ];

        return [];
    }

    /**
     * Format guild in to an array.
     *
     * @param $guild
     * @return array
     */
    private function formatGuild($guild)
    {
        $text = explode('of the', $guild);

        return [
            'name' => $this->clearString($text[1]),
            'rank' => $this->clearString($text[0])
        ];
    }

    /**
     * Remove invisible chars from string.
     *
     * @param $string
     * @return mixed
     */
    private function clearString($string)
    {
        $string = preg_replace('/[\x00-\x1F\x7F-\xFF]/', ' ', trim($string));

        return preg_replace('/\s+/', ' ', $string);
    }

    /**
     * Format Label
     *
     * @param $label
     * @return mixed|string
     */
    private function formatLabel($label)
    {
        $label = str_replace('-', '_', str_slug($label));

        return $label == 'guild_membership' ? 'guild' : $label;
    }

    /**
     * Get content from character box.
     *
     * @param $box
     * @return mixed
     */
    public function getCharacterBox($box)
    {
        $index = $this->getCharacterBoxIndexes($box);

        return $index !== false
            ? $this->getCrawler()
                ->filterXPath('//div[@class="BoxContent"]')
                ->filter('table')
                ->eq($index)
            : false;
    }

    /**
     * Get the index of the required box.
     *
     * @param $box
     * @return false|int|string
     */
    private function getCharacterBoxIndexes($box)
    {
        $boxes = [
            'detail'       => 'Character Information',
            'achievements' => 'Account Achievements',
            'deaths'       => 'Character Deaths',
            'information'  => 'Account Information',
            'characters'   => 'Characters'
        ];

        $tables = array_filter($this->getCrawler()->filterXPath('//div[@class="BoxContent"]')
            ->filter('table')->each(function ($table) {
                return $this->clearString($table->filter('tr')->first()->children('td')->text());
            }), function ($table) {
            return ! empty($table) && $table != 'Search Character' && $table != 'Name:';
        });

        return array_search($boxes[$box], $tables, true);
    }

    /**
     * Crawl the character page.
     *
     * @return mixed
     */
    private function getCrawler()
    {
        $client = new Client();

        return $client->request('GET', "https://secure.tibia.com/community/?subtopic=characters&name={$this->name}");
    }

}