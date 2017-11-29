<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HuntingSpot extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'tips',
        'experience',
        'profit',
        'place',
        'level_min',
        'level_max',
        'require_premium',
        'require_quest',
        'has_task',
        'profitable',
        'soloable',
        'author'
    ];

    /**
     * A hunting spot belongs to many vocations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vocations()
    {
        return $this->belongsToMany(Vocation::class);
    }

    /**
     * A hunting spot belongs to many creatures.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function creatures()
    {
        return $this->belongsToMany(Creature::class);
    }

    /**
     * A hunting spot belongs to many items with pivot amount.
     *
     * @return $this
     */
    public function supplies()
    {
        return $this->belongsToMany(Item::class)->withPivot('amount');
    }

    /**
     * A hunting spot has many equipments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipments()
    {
        return $this->hasMany(HuntingSpotEquipment::class);
    }
}
