<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\Models\ShippingCarrier;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DeleteShippingCarrierAction
{
    /**
     * @param int $id
     * @return bool
     */
    public function execute($id)
    {
        $shippingCarrier = $this->findShippingCarrier($id);

        if (! $this->deleteShippingCarrier($shippingCarrier)) {
            return false;
        }

        $this->clearCache($shippingCarrier);

        return true;
    }

    /**
     * @param int $id
     * @return \Hzmwdz\TinyShipping\Models\ShippingCarrier
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function findShippingCarrier($id)
    {
        $shippingCarrier = ShippingCarrier::find($id);

        if (! $shippingCarrier) {
            throw new BusinessException(TransHelper::shippingCarrierNotFound($id));
        }

        return $shippingCarrier;
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\ShippingCarrier $shippingCarrier
     * @return bool
     */
    protected function deleteShippingCarrier($shippingCarrier)
    {
        return DB::transaction(function () use ($shippingCarrier) {
            $shippingCarrier->shippingRules->each(function ($shippingRule) {
                $shippingRule->shippingRegions()->delete();

                $shippingRule->delete();
            });

            return $shippingCarrier->delete();
        });
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\ShippingCarrier $shippingCarrier
     * @return void
     */
    protected function clearCache($shippingCarrier)
    {
        Cache::forget(CacheHelper::keyForAllShippingCarriers());

        Cache::forget(CacheHelper::keyForShippingCarrier($shippingCarrier->id));
    }
}
