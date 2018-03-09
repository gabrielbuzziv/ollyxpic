<?php

namespace App\Http\Controllers;

use App\DiscordGuild;
use App\Http\Requests\DiscordGuildRequest;
use Illuminate\Http\Request;

class DiscordGuildController extends ApiController
{

    /**
     * Create a guild.
     */
    public function create()
    {
        $this->validate(request(), [
            'guild_id' => 'required',
            'guild_name' => 'required'
        ]);

        try {
            $guild = (new DiscordGuild)->updateOrCreate(['guild_id' => request('guild_id')]);
            $guild->name = request('guild_name');
            $guild->save();

            return $this->respond($guild->toArray());
        } catch (Exception $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * Delete guild.
     */
    public function destroy()
    {
        try {
            $guild = DiscordGuild::where('guild_id', request('guild_id'))->first();
            $guild->delete();
        } catch (Exception $e) {
            $this->respondInternalError($e);
        }
    }
}
