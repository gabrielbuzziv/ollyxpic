<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemProperties extends Model
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
    protected $table = 'ItemProperties';
}
