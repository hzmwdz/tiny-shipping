<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\DTOs\ShippingRegionDTO;
use Hzmwdz\TinyShipping\DTOs\ShippingRuleDTO;
use Hzmwdz\TinyShipping\Models\ShippingCarrier;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Hzmwdz\TinyShipping\Validators\ShippingRuleValidator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CreateShippingRuleAction
{
    /**
     * @param int $shippingCarrierId
     * @param array $data
     * @return \Hzmwdz\TinyShipping\Models\ShippingRule
     */
    public function execute($shippingCarrierId, $data)
    {
        $validated = ShippingRuleValidator::validate($data);

        $dto = ShippingRuleDTO::fromArray($validated);

        $shippingCarrier = $this->findShippingCarrier($shippingCarrierId);

        $shippingRule = $this->createShippingRule($shippingCarrier, $dto);

        $this->clearCache($shippingRule);

        return $shippingRule;
    }

    /**
     * @param int $shippingCarrierId
     * @return \Hzmwdz\TinyShipping\Models\ShippingCarrier
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function findShippingCarrier($shippingCarrierId)
    {
        $shippingCarrier = ShippingCarrier::find($shippingCarrierId);

        if (! $shippingCarrier) {
            throw new BusinessException(TransHelper::shippingCarrierNotFound($shippingCarrierId));
        }

        return $shippingCarrier;
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\ShippingCarrier $shippingCarrier
     * @param \Hzmwdz\TinyShipping\DTOs\ShippingRuleDTO $dto
     * @return \Hzmwdz\TinyShipping\Models\ShippingRule
     */
    protected function createShippingRule($shippingCarrier, $dto)
    {
        return DB::transaction(function () use ($shippingCarrier, $dto) {
            $shippingRule = $shippingCarrier->shippingRules()->create([
                'title' => $dto->title,
                'base_rate' => $dto->baseRate,
                'weight_rate' => $dto->weightRate,
            ]);

            $dto->shippingRegions->each(function (ShippingRegionDTO $regionDTO) use ($shippingRule) {
                $shippingRule->shippingRegions()->create([
                    'region_level_1_id' => $regionDTO->regionLevel1Id,
                    'region_level_1_name' => $regionDTO->regionLevel1Name,
                    'region_level_2_id' => $regionDTO->regionLevel2Id,
                    'region_level_2_name' => $regionDTO->regionLevel2Name,
                    'region_level_3_id' => $regionDTO->regionLevel3Id,
                    'region_level_3_name' => $regionDTO->regionLevel3Name,
                ]);
            });

            return $shippingRule;
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
