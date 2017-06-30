<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HuntTeammates extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'hunt_id',
        'character',
        'waste',
        'profit'
    ];

    /**
     * A teammate belongs to a hunt.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hunt()
    {
        return $this->belongsTo(Hunts::class);
    }
}
