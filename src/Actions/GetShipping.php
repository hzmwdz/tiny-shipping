<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyShipping\Contracts\GetShipping as GetShippingContract;
use Hzmwdz\TinyShipping\Models\Shipping;
use Hzmwdz\TinyShipping\Support\CacheKeyHelper;
use Illuminate\Support\Facades\Cache;

class GetShipping implements GetShippingContract
{
    /**
     * @var int
     */
    protected $ttl = 7200;

    /**
     * @param int $id
     * @return \Hzmwdz\TinyShipping\Models\Shipping|null
     */
    public function execute($id)
    {
        $cacheKey = CacheKeyHelper::shippingItem($id);

        return Cache::remember($cacheKey, $this->ttl, function () use ($id) {
            return Shipping::with('rules')->find($id);
        });
    }
}
