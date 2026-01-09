<?php

namespace Hzmwdz\TinyShipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRule extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'shipping_id',
        'region_level1_id',
        'region_level1_name',
        'region_level1_native',
        'region_level2_id',
        'region_level2_name',
        'region_level2_native',
        'region_level3_id',
        'region_level3_name',
        'region_level3_native',
        'base_rate',
        'weight_rate',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
}
