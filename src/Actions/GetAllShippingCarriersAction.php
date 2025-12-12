<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyShipping\Models\ShippingCarrier;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Illuminate\Support\Facades\Cache;

class GetAllShippingCarriersAction
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function execute()
    {
        $cacheKey = CacheHelper::keyForAllShippingCarriers();

        return Cache::remember($cacheKey, CacheHelper::ttl(), function () {
            return ShippingCarrier::get();
        });
    }
}
