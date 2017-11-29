<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HuntingSpotEquipment extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'hunting_spot_id',
        'title',
        'description',
        'amulet_id',
        'helmet_id',
        'weapon_id',
        'armor_id',
        'shield_id',
        'ring_id',
        'boots_id',
        'ammunition_id'
    ];

    /**
     * A equipment belongs to a hunting spot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function huntingSpot()
    {
        return $this->belongsTo(HuntingSpot::class);
    }

    /**
     * A equipment belongs to a amulet item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function amulet()
    {
        return $this->belongsTo(Item::class, 'amulet_id');
    }

    /**
     * A equipment belongs to a helmet item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function helmet()
    {
        return $this->belongsTo(Item::class, 'helmet_id');
    }

    /**
     * A equipment belongs to a weapon item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weapon()
    {
        return $this->belongsTo(Item::class, 'weapon_id');
    }

    /**
     * A equipment belongs to a armor item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function armor()
    {
        return $this->belongsTo(Item::class, 'armor_id');
    }

    /**
     * A equipment belongs to a shield item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shield()
    {
        return $this->belongsTo(Item::class, 'shield_id');
    }

    /**
     * A equipment belongs to a ring item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ring()
    {
        return $this->belongsTo(Item::class, 'ring_id');
    }

    /**
     * A equipment belongs to a boots item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function boots()
    {
        return $this->belongsTo(Item::class, 'boots_id');
    }

    /**
     * A equipment belongs to a ammunition item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ammunition()
    {
        return $this->belongsTo(Item::class, 'ammunition_id');
    }
}
