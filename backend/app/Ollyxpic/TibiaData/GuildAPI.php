<?php

namespace App\Ollyxpic\TibiaData;


class GuildAPI
{

    protected $name;

    /**
     * GuildAPI constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function get()
    {
        $guildApi = $this->getGuildFromApi();

        return [
            'details' => $this->getDetails($guildApi),
            'members' => $this->getMembers($guildApi)
        ];
    }

    /**
     * Get guild details.
     *
     * @param $data
     * @return array
     */
    private function getDetails($data)
    {
        $data = $data->guild->data;

        return [
            'name'        => $data->name,
            'description' => $data->description,
            'application' => $data->application,
            'founded'     => $data->founded,
            'active'      => $data->active,
            'avatar'      => $data->guildlogo
        ];
    }

    private function getMembers($data)
    {
        $members = array_map(function ($members) {
            return $members->characters;
        }, $data->guild->members);
        dd($members);
//        return array_reduce($data->guild->members, function ($members, $member) {
//            $members[] = [
//
//            ];
//        }, []);
    }

    /**
     * Request the guild from API.
     *
     * @return mixed
     */
    private function getGuildFromApi()
    {
        $url = "https://api.tibiadata.com/v2/guild/{$this->name}.json";

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