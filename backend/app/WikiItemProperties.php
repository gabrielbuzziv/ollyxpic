<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiItemProperties extends Model
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

    /**
     * A WikiItemProperties belongs to a WikiItems.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(WikiItems::class, 'itemid');
    }
}
