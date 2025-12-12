<?php

namespace Hzmwdz\TinyShipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRule extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'shipping_carrier_id',
        'title',
        'base_rate',
        'weight_rate',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingCarrier()
    {
        return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippingRegions()
    {
        return $this->hasMany(ShippingRegion::class, 'shipping_rule_id');
    }
}
