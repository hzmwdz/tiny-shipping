<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyShipping\Contracts\GetShippingList as GetShippingListContract;
use Hzmwdz\TinyShipping\Models\Shipping;
use Hzmwdz\TinyShipping\Support\CacheKeyHelper;
use Illuminate\Support\Facades\Cache;

class GetShippingList implements GetShippingListContract
{
    /**
     * @var int
     */
    protected $ttl = 7200;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function execute()
    {
        $cacheKey = CacheKeyHelper::shippingList();

        return Cache::remember($cacheKey, $this->ttl, function () {
            return Shipping::get();
        });
    }
}
