<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Change extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['title', 'body', 'user_id'];

    /**
     * A change belongs to a user(author).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
