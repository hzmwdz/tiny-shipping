<?php

namespace Hzmwdz\TinyShipping\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
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
    public function rules()
    {
        return $this->hasMany(ShippingRule::class);
    }
}
