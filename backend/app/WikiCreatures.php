<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiCreatures extends Model
{
    /**
     * Database connection.
     *
     * @var string
     */
    protected $connection = 'sqlite';

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'Creatures';

    /**
     * The hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['image'];

    public function drops()
    {
        return $this->belongsToMany(WikiItems::class, 'CreatureDrops', 'creatureid', 'itemid')
            ->withPivot('percentage')
            ->withPivot('min')
            ->withPivot('max');
    }
}
