<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\Models\ShippingRule;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DeleteShippingRuleAction
{
    /**
     * @param int $shippingCarrierId
     * @param int $id
     * @return bool
     */
    public function execute($shippingCarrierId, $id)
    {
        $shippingRule = $this->findShippingRule($shippingCarrierId, $id);

        if (! $this->deleteShippingRule($shippingRule)) {
            return false;
        }

        $this->clearCache($shippingRule);

        return true;
    }

    /**
     * @param int $shippingCarrierId
     * @param int $id
     * @return \Hzmwdz\TinyShipping\Models\ShippingRule
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function findShippingRule($shippingCarrierId, $id)
    {
        $shippingRule = ShippingRule::where('id', $id)
            ->where('shipping_carrier_id', $shippingCarrierId)
            ->first();

        if (! $shippingRule) {
            throw new BusinessException(TransHelper::shippingRuleNotFound($id));
        }

        return $shippingRule;
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\ShippingRule $shippingRule
     * @return bool
     */
    protected function deleteShippingRule($shippingRule)
    {
        return DB::transaction(function () use ($shippingRule) {
            $shippingRule->shippingRegions()->delete();

            return $shippingRule->delete();
        });
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\ShippingRule $shippingRule
     * @return void
     */
    protected function clearCache($shippingRule)
    {
        Cache::forget(CacheHelper::keyForShippingCarrier($shippingRule->shipping_carrier_id));
    }
}
