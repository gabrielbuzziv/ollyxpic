<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiWorldObjects extends Model
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
    protected $table = 'WorldObjects';

    /**
     * The hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['image'];
}
