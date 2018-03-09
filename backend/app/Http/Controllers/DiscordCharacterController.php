<?php

namespace App\Http\Controllers;

use App\DiscordCharacter;
use Illuminate\Http\Request;

class DiscordCharacterController extends ApiController
{

    /**
     * Add a character.
     */
    public function add()
    {
        $this->validate(request(), [
            'guild_id' => 'required',
            'name'     => 'required',
            'type'     => 'required'
        ]);

        try {
            $guild = (int) request('guild_id');
            $name = request('name');
            $type = request('type');
            $player = $this->searchPlayer($name)->characters;

            if (isset($player->error)) {
                return $this->respondInternalError(null, 'Character not found!');
            }

            $character = (new DiscordCharacter)->updateOrCreate(['guild_id' => $guild, 'character' => $player->data->name]);
            $character->level = $player->data->level;
            $character->vocation = $player->data->vocation;
            $character->world = $player->data->world;
            $character->type = $type;
            $character->save();

            return $this->respond($character->toArray());
        } catch (Exception $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * Get player from API.
     *
     * @param $name
     * @return mixed
     */
    private function searchPlayer($name)
    {
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
