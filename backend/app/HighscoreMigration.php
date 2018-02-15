<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HighscoreMigration extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['type', 'results', 'migration_date'];

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'highscores_migrations';


}
