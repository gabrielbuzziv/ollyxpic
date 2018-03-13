<?php

namespace App\Http\Controllers;

use App\HighscoreMigration;
use App\Highscores;
use App\HighscoresSkills;
use App\Ollyxpic\Crawlers\Character;
use App\Player;
use App\World;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $player = Player::with(['world', 'deaths'])->find($this->getPlayer($name)->id);

        return $this->respond($player->toArray());
    }

    /**
     * Get player skills.
     *
     * @param Player $player
     * @return mixed
     */
    public function skills(Player $player)
    {
        return $this->respond($this->getSkills($player->name)->toArray());
    }

    /**
     * Get months with experience.
     *
     * @return mixed
     */
    public function months()
    {
        $months = (new HighscoreMigration)
            ->select(DB::raw('date_format(migration_date, "%Y-%m") as month'))
            ->where('type', 'experience')
            ->groupBy(DB::raw('YEAR(migration_date), MONTH(migration_date) '))
            ->orderByRaw('YEAR(migration_date) DESC, MONTH(migration_date) DESC ')
            ->get();

        $months = array_map(function ($month) {
            return $month['month'];
        }, $months->toArray());

        return $this->respond($months);
    }

    /**
     * Get current player level.
     *
     * @param Player $player
     * @return mixed
     */
    public function level(Player $player)
    {
        $experience = (new Highscores)
            ->where('name', $player->name)
            ->where('active', 1)
            ->where('type', 'experience')
            ->orderBy('updated_at', 'desc')
            ->first();

        return $this->respond($experience->toArray());
    }

    /**
     * Get player experience advances.
     *
     * @param Player $player
     * @return mixed
     */
    public function experience(Player $player)
    {
        $start = Carbon::createFromFormat('Y-m', request('month'))->firstOfMonth()->subDay();
        $end = Carbon::createFromFormat('Y-m', request('month'))->lastOfMonth();

        $experience = (new Highscores)
            ->where('type', 'experience')
            ->where('name', $player->name)
            ->whereBetween('updated_at', [$start, $end])
            ->orderBy('updated_at', 'asc')
            ->get();

        return $this->respond($experience->toArray());
    }

    /**
     * Get a experience overview.
     *
     * @param Player $player
     * @return mixed
     */
    public function overview(Player $player)
    {
        $experience = (new Highscores)
            ->where('type', 'experience')
            ->where('name', $player->name)
            ->orderBy('updated_at', 'asc')
            ->get();

        return $this->respond($experience->toArray());
    }

    /**
     * Get all data to compare.
     *
     * @param $name
     * @return mixed
     */
    public function compare($name)
    {
        $player = Player::with(['world', 'deaths'])->find($this->getPlayer($name)->id);

        $months = (new HighscoreMigration)
            ->select(DB::raw('date_format(migration_date, "%Y-%m") as month'))
            ->where('type', 'experience')
            ->groupBy(DB::raw('YEAR(migration_date), MONTH(migration_date) '))
            ->orderByRaw('YEAR(migration_date) asc, MONTH(migration_date) asc ')
            ->take(2)
            ->get();


        $lastMonth = (new Highscores)
            ->where('type', 'experience')
            ->where('name', $player->name)
            ->whereBetween('updated_at', [
                Carbon::createFromFormat('Y-m', $months[0]->month)->firstOfMonth()->subDay()->format('Y-m-d'),
                Carbon::createFromFormat('Y-m', $months[0]->month)->lastOfMonth()->format('Y-m-d')
            ])
            ->orderBy('updated_at', 'asc')
            ->get();

        $month = (new Highscores)
            ->where('type', 'experience')
            ->where('name', $player->name)
            ->whereBetween('updated_at', [
                Carbon::createFromFormat('Y-m', $months[1]->month)->firstOfMonth()->subDay()->format('Y-m-d'),
                Carbon::createFromFormat('Y-m', $months[1]->month)->lastOfMonth()->format('Y-m-d')
            ])
            ->orderBy('updated_at', 'asc')
            ->get();

        return $this->respond([
            'details'    => $player,
            'skills'     => $this->getSkills($player->name),
            'experience' => [
                'last'    => $lastMonth,
                'current' => $month
            ]
        ]);
    }

    /**
     * Update highscores.
     *
     * @param $player
     */
    private function updateHighscores($player)
    {
        if (isset($player->former_names)) {
            $former_names = explode(', ', $player->former_names);

            foreach ($former_names as $name) {
                (new Highscores)->where('name', $name)->update(['name' => $player->name]);
            }
        }
    }

    /**
     * Update player.
     *
     * @param $object
     * @param $player
     */
    private function updatePlayer($object, $player)
    {
        $details = (object) $player['details'];
        $deaths = $player['deaths'];

        $object->name = $details->name;
        $object->former_names = isset($details->former_names) ? implode(', ', $details->former_names) : null;
        $object->vocation = $details->vocation;
        $object->level = $details->level;
        $object->residence = $details->residence;
        $object->house = isset($details->house) ? $details->house['town'] : null;
        $object->gender = $details->sex;
        $object->married_to = isset($details->married_to) ? $details->married_to : null;
        $object->description = isset($details->comment) ? $details->comment : null;
        $object->guild = isset($details->guild) ? $details->guild['name'] : null;
        $object->premium = $details->account_status == 'Premium Account' ? true : false;
        $object->achievements = $details->achievement_points;
        $object->world_id = World::where('name', $details->world)->first()->id;
        $object->last_login = $details->last_login;
        $object->save();

        foreach ($deaths as $death) {
            $death = (object) $death;

            $object->deaths()->firstOrCreate([
                'level'    => $death->level,
                'reason'   => $death->reason,
                'involved' => serialize($death->involved),
                'died_at'  => $death->date,
                'type'     => $death->type,
            ]);
        }

        return $object;
    }

    /**
     * Get player.
     *
     * @param $name
     * @return Player|\Illuminate\Database\Eloquent\Model|mixed|null|static
     */
    private function getPlayer($name)
    {
        $name = strtolower(trim($name));
        $clearName = urlencode($name);

        $playerExists = (new Player)
            ->with(['world', 'deaths'])
            ->where('name', $name)
            ->orWhereRaw("'{$clearName}' in (former_names)")
            ->first();

        $player = $this->searchPlayer($name);
        $player = $playerExists ? $this->updatePlayer($playerExists, $player) : $this->updatePlayer(new Player(), $player);
        $this->updateHighscores($player);

        return $player;
    }

    /**
     * Get player from API.
     *
     * @param $name
     * @return mixed
     */
    private function searchPlayer($name)
    {
        return (new Character($name))->run();
    }

    /**
     * Get skills.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    private function getSkills($name)
    {
        return (new HighscoresSkills)
            ->select('skill as level', 'type as skill')
            ->where('name', $name)
            ->whereIn('type', ['magic', 'axe', 'club', 'sword', 'distance', 'shielding', 'achievements', 'loyalty'])
            ->groupBy('type')
            ->orderBy('updated_at', 'desc')
            ->get();
    }
}
