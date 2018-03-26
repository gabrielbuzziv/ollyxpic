<?php

namespace App\Ollyxpic\TibiaData;

use Carbon\Carbon;

class CharacterAPI
{

    /**
     * Name
     *
     * @var
     */
    protected $name;

    /**
     * CharacterAPI constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get API.
     *
     * @return array|bool
     */
    public function get()
    {
        $characterAPI = $this->getCharacterFromApi();

        if ( ! $this->isValidRequest($characterAPI)) return [];

        return [
            'details' => $this->getDetails($characterAPI),
            'deaths'  => $this->getDeaths($characterAPI)
        ];
    }

    /**
     * In case the request fail return false.
     *
     * @param $data
     * @return bool
     */
    private function isValidRequest($data)
    {
        return ! isset($data->characters) || isset($data->characters->error) ? false : true;
    }

    /**
     * @param $data
     * @return arrayGet character details.
     */
    private function getDetails($data)
    {
        $data = $data->characters->data;

        return [
            'name'               => $data->name,
            'former_names'       => isset($data->former_names) ? $data->former_names : null,
            'sex'                => $data->sex,
            'vocation'           => $data->vocation,
            'level'              => $data->level,
            'achievement_points' => $data->achievement_points,
            'world'              => $data->world,
            'residence'          => isset($data->residence) ? $data->residence : null,
            'guild'              => isset($data->guild) ? $data->guild : null,
            'last_login'         => Carbon::createFromFormat('Y-m-d H:i:s.u', $data->last_login[0]->date, 'Europe/Berlin')->format('Y-m-d H:i:s'),
            'comment'            => isset($data->comment) ? $data->comment : null,
            'account_status'     => $data->account_status,
        ];
    }

    /**
     * Get deaths.
     *
     * @param $data
     * @return array
     */
    private function getDeaths($data)
    {
        if ( ! isset($data->characters->deaths)) return [];

        $data = $data->characters->deaths;
        $deaths = [];

        foreach ($data as $death) {
            $deaths[] = [
                'date'     => Carbon::createFromFormat('Y-m-d H:i:s.u', $death->date->date, 'Europe/Berlin')->format('Y-m-d H:i:s'),
                'level'    => $death->level,
                'reason'   => $death->reason,
                'involved' => $death->involved,
            ];
        }

        return $deaths;
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
     * Request the player from API.
     *
     * @return mixed
     */
    private function getCharacterFromApi()
    {
        $name = urlencode(strtolower($this->name));
        $url = "https://api.tibiadata.com/v2/characters/{$name}.json";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        return json_decode(curl_exec($ch));
    }
}