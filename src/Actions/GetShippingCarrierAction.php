<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyShipping\Models\ShippingCarrier;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Illuminate\Support\Facades\Cache;

class GetShippingCarrierAction
{
    /**
     * @param int $id
     * @return \Hzmwdz\TinyShipping\Models\ShippingCarrier|null
     */
    public function execute($id)
    {
        $cacheKey = CacheHelper::keyForShippingCarrier($id);

        return Cache::remember($cacheKey, CacheHelper::ttl(), function () use ($id) {
            return ShippingCarrier::with('shippingRules.shippingRegions')->find($id);
        });
    }
}
