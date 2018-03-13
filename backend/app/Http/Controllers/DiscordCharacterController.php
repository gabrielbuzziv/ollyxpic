<?php

namespace App\Http\Controllers;

use App\DiscordCharacter;
use App\Ollyxpic\Character;
use Carbon\Carbon;
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
            $player = $this->searchPlayer($name);

            $recentDeath = Carbon::now()->timezone('America/New_York');

            if (count($player['deaths']) > 0) {
                $recentDeath = Carbon::createFromFormat('Y-m-d H:i:s', $player['deaths'][0]['date'], 'Europe/Berlin')->timezone('America/New_York');
            }

            $character = (new DiscordCharacter)->updateOrCreate([
                'guild_id' => $guild,
                'character' => $player['details']['name']
            ]);
            $character->level = $player['details']['level'];
            $character->vocation = $player['details']['vocation'];
            $character->world = $player['details']['world'];
            $character->type = $type;
            $character->last_death = $recentDeath;
            $character->save();

            return $this->respond($character->toArray());
        } catch (Exception $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * Remove player from list.
     *
     * @return bool|mixed|null
     */
    public function remove()
    {
        $this->validate(request(), [
            'guild_id' => 'required',
            'name'     => 'required',
            'type'     => 'required'
        ]);

        $guild = (int) request('guild_id');
        $name = request('name');
        $type = request('type');

        try {
            return (new DiscordCharacter)
                ->where('guild_id', $guild)
                ->where('character', $name)
                ->where('type', $type)
                ->delete();
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
        return (new Character())->check($name);
    }
}
