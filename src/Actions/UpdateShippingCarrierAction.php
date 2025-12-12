<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\DTOs\ShippingCarrierDTO;
use Hzmwdz\TinyShipping\Models\ShippingCarrier;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Hzmwdz\TinyShipping\Validators\ShippingCarrierValidator;
use Illuminate\Support\Facades\Cache;

class UpdateShippingCarrierAction
{
    /**
     * @param int $id
     * @param array $data
     * @return \Hzmwdz\TinyShipping\Models\ShippingCarrier
     */
    public function execute($id, $data)
    {
        $shippingCarrier = $this->findShippingCarrier($id);

        $validated = ShippingCarrierValidator::validate($data, $shippingCarrier->id);

        $dto = ShippingCarrierDTO::fromArray($validated);

        $shippingCarrier->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'base_rate' => $dto->baseRate,
            'weight_rate' => $dto->weightRate,
        ]);

        $this->clearCache($shippingCarrier);

        return $shippingCarrier;
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
     * @return void
     */
    protected function clearCache($shippingCarrier)
    {
        Cache::forget(CacheHelper::keyForAllShippingCarriers());

        Cache::forget(CacheHelper::keyForShippingCarrier($shippingCarrier->id));
    }
}
