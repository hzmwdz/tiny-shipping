<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\Contracts\DeleteShipping as DeleteShippingContract;
use Hzmwdz\TinyShipping\Models\Shipping;
use Hzmwdz\TinyShipping\Support\CacheKeyHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DeleteShipping implements DeleteShippingContract
{
    /**
     * @param int $id
     * @return bool
     */
    public function execute($id)
    {
        return DB::transaction(function () use ($id) {
            $shipping = $this->findShipping($id);

            $this->deleteShippingRules($shipping);

            $shipping->delete();

            $this->clearCache($shipping);

            return true;
        });
    }

    /**
     * @param int $id
     * @return \Hzmwdz\TinyShipping\Models\Shipping
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function findShipping($id)
    {
        $shipping = Shipping::find($id);

        if (!$shipping) {
            throw new BusinessException(
                TransHelper::shippingNotFound($id)
            );
        }

        return $shipping;
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\Shipping $shipping
     * @return void
     */
    protected function deleteShippingRules($shipping)
    {
        $shipping->rules()->delete();
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\Shipping $shipping
     * @return void
     */
    protected function clearCache($shipping)
    {
        Cache::forget(CacheKeyHelper::shippingList());

        Cache::forget(CacheKeyHelper::shippingItem($shipping->id));
    }
}
