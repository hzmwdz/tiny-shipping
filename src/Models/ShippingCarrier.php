<?php

namespace Hzmwdz\TinyShipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCarrier extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'base_rate',
        'weight_rate',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippingRules()
    {
        return $this->hasMany(ShippingRule::class, 'shipping_carrier_id');
    }
}
