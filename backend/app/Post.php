<?php

namespace App;

use Carbon\Carbon;
use App\Ollyxpic\Truncate;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'active',
        'source',
        'created_at',
        'hotnews',
        'comments',
        'link',
        'slug'
    ];

    protected $appends = ['resume'];

    /**
     * Set created at attribute.
     *
     * @param $value
     */
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = empty($value) ? Carbon::now() : Carbon::createFromFormat('d-m-Y H:i', $value);
    }

    /**
     * Get created at data and reformat.
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d-m-Y H:i');
    }

    /**
     * A news belongs to an author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Create a truncate content of body.
     *
     * @return bool|string
     */
    public function getResumeAttribute()
    {
        return Truncate::truncate($this->body, 400);
    }
}
