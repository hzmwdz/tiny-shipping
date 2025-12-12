<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyShipping\DTOs\ShippingCarrierDTO;
use Hzmwdz\TinyShipping\Models\ShippingCarrier;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Hzmwdz\TinyShipping\Validators\ShippingCarrierValidator;
use Illuminate\Support\Facades\Cache;

class CreateShippingCarrierAction
{
    /**
     * @param array $data
     * @return \Hzmwdz\TinyShipping\Models\ShippingCarrier
     */
    public function execute($data)
    {
        $validated = ShippingCarrierValidator::validate($data);

        $dto = ShippingCarrierDTO::fromArray($validated);

        $shippingCarrier = ShippingCarrier::create([
            'name' => $dto->name,
            'code' => $dto->code,
            'base_rate' => $dto->baseRate,
            'weight_rate' => $dto->weightRate,
        ]);

        $this->clearCache();

        return $shippingCarrier;
    }

    /**
     * @return void
     */
    protected function clearCache()
    {
        Cache::forget(CacheHelper::keyForAllShippingCarriers());
    }
}
