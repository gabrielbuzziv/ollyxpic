<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spells extends Model
{

    /**
     * Database connection.
     *
     * @var string
     */
    protected $connection = 'sqlite';

    /**
     * Database table.
     *
     * @var string
     */
    protected $table = 'Spells';

    /**
     * Disable timestamp.
     *
     * @var bool
     */
    public $timestamps = false;
}
