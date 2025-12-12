<?php

namespace Hzmwdz\TinyShipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRegion extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'shipping_rule_id',
        'region_level_1_id',
        'region_level_1_name',
        'region_level_2_id',
        'region_level_2_name',
        'region_level_3_id',
        'region_level_3_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingRule()
    {
        return $this->belongsTo(ShippingRule::class, 'shipping_rule_id');
    }
}
