<?php

namespace App\Http\Controllers;

use App\Highscores;
use App\Player;
use App\World;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlayersController extends ApiController
{

    /**
     * Get player information.
     *
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($name)
    {
        $name = strtolower(trim($name));
        $player = (new Player)
            ->with(['world', 'deaths'])
            ->where('name', $name)
            ->orWhereRaw("'{$name}' in (former_names)")
            ->first();

        if ($player) {
            if (Carbon::now()->diffInMinutes($player->updated_at) >= 15) {
                $api = $this->getPlayer($name);
                $this->updatePlayer($player, $api);
            }

            return $this->respond($player->toArray());
        }

        $api = $this->getPlayer($name);

        if (isset($api->details->characters->error)) {
            return $this->respondNotFound(null);
        }

        $player = new Player();
        $this->updatePlayer($player, $api);

        return $this->respond(Player::with(['world', 'deaths'])->find($player->id)->toArray());
    }

    /**
     * Update player.
     *
     * @param $player
     * @param $api
     */
    private function updatePlayer($player, $api)
    {
        $details = $api->characters->data;
        $deaths = $api->characters->deaths;

        $player->name = $details->name;
        $player->former_names = isset($details->former_names) ? implode(', ', $details->former_names) : null;
        $player->vocation = $details->vocation;
        $player->level = $details->level;
        $player->residence = $details->residence;
        $player->house = isset($details->house) ? $details->house->town : null;
        $player->gender = $details->sex;
        $player->married_to = isset($details->married_to) ? $details->married_to : null;
        $player->guild = isset($details->guild) ? $details->guild->name : null;
        $player->premium = $details->account_status == 'Premium Account' ? true : false;
        $player->achievements = $details->achievement_points;
        $player->world_id = World::where('name', $details->world)->first()->id;
        $player->last_login = Carbon::createFromFormat('Y-m-d H:i:s.u', $details->last_login[0]->date);
        $player->save();

        array_walk($deaths, function ($death) use ($player) {
            $player->deaths()->firstOrCreate([
                'level'    => $death->level,
                'reason'   => $death->reason,
                'involved' => serialize($death->involved),
                'died_at'  => Carbon::createFromFormat('Y-m-d H:i:s.u', $death->date->date),
            ]);
        });
    }

    /**
     * Get player from API.
     *
     * @param $name
     * @return mixed
     */
    private function getPlayer($name)
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

    /**
     * Get skills
     *
     * @param $name
     * @param $type
     */
    private function getSkills($name, $type)
    {
        return (new Highscores)
            ->$type()
            ->where('name', $name)
            ->orderBy('updated_at', 'desc')
            ->first();
    }
}
