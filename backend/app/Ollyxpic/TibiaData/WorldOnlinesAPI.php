<?php

namespace App\Ollyxpic\TibiaData;

class WorldOnlinesAPI
{

    /**
     * World
     *
     * @var
     */
    protected $world;

    /**
     * WorldOnlinesAPI constructor.
     * @param $world
     */
    public function __construct($world)
    {
        $this->world = $world;
    }

    /**
     * Get players online from world;
     *
     * @return array
     */
    public function get()
    {
        $onlineAPI = $this->getOnlinesFromApi();

        if ( ! $this->isValidRequest($onlineAPI)) return [];

        return $this->getOnlines($onlineAPI);
    }

    /**
     * In case the request fail return false.
     *
     * @param $data
     * @return bool
     */
    private function isValidRequest($data)
    {
        return ! isset($data->world) && ! isset($data->world->players_online) ? false : true;
    }

    /**
     * Get players online.
     *
     * @param $data
     * @return array
     */
    private function getOnlines($data)
    {
        if ( ! isset($data->world->players_online)) return [];

        $data = $data->world->players_online;
        $onlines = [];

        foreach ($data as $online) {
            $onlines[] = [
                'character' => $online->name,
                'level'     => $online->level,
                'vocation'  => $online->vocation
            ];
        }

        return $onlines;
    }

    /**
     * Get online from worlds.
     *
     * @return mixed
     */
    private function getOnlinesFromApi()
    {
        $url = "https://api.tibiadata.com/v2/world/{$this->world}.json";

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