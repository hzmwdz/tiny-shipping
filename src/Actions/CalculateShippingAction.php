<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\DTOs\CalculateShippingDTO;
use Hzmwdz\TinyShipping\Models\ShippingCarrier;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Hzmwdz\TinyShipping\Validators\CalculateShippingValidator;
use Illuminate\Support\Facades\Cache;

class CalculateShippingAction
{
    /**
     * @param array $data
     * @return float
     */
    public function execute($data)
    {
        $validated = CalculateShippingValidator::validate($data);

        $dto = CalculateShippingDTO::fromArray($validated);

        $shippingCarrier = $this->findShippingCarrier($dto->shippingCarrierId);

        $shippingRule = $this->matchShippingRule($shippingCarrier, $dto);

        if ($shippingRule) {
            return $this->calculate($shippingRule->base_rate, $shippingRule->weight_rate, $dto->weightKg);
        }

        return $this->calculate($shippingCarrier->base_rate, $shippingCarrier->weight_rate, $dto->weightKg);
    }

    /**
     * @param int $shippingCarrierId
     * @return \Hzmwdz\TinyShipping\Models\ShippingCarrier
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function findShippingCarrier($shippingCarrierId)
    {
        $cacheKey = CacheHelper::keyForShippingCarrier($shippingCarrierId);

        $shippingCarrier = Cache::remember($cacheKey, CacheHelper::ttl(), function () use ($shippingCarrierId) {
            return ShippingCarrier::with('shippingRules.shippingRegions')->find($shippingCarrierId);
        });

        if (! $shippingCarrier) {
            throw new BusinessException(TransHelper::shippingCarrierNotFound($shippingCarrierId));
        }

        return $shippingCarrier;
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\ShippingCarrier $shippingCarrier
     * @param \Hzmwdz\TinyShipping\DTOs\CalculateShippingDTO $dto
     * @return \Hzmwdz\TinyShipping\Models\ShippingRule|null
     */
    protected function matchShippingRule($shippingCarrier, $dto)
    {
        $bestShippingRule = null;

        $bestScore = 0;

        foreach ($shippingCarrier->shippingRules as $shippingRule) {
            foreach ($shippingRule->shippingRegions as $shippingRegion) {
                $currentScore = 0;

                if (isset($shippingRegion->region_level_3_id)) {
                    if ($shippingRegion->region_level_3_id == $dto->regionLevel3Id) {
                        $currentScore = 3;
                    }
                } else {
                    if (isset($shippingRegion->region_level_2_id)) {
                        if ($shippingRegion->region_level_2_id == $dto->regionLevel2Id) {
                            $currentScore = 2;
                        }
                    } else {
                        if ($shippingRegion->region_level_1_id == $dto->regionLevel1Id) {
                            $currentScore = 1;
                        }
                    }
                }

                if ($currentScore > $bestScore) {
                    $bestScore = $currentScore;
                    $bestShippingRule = $shippingRule;
                }
            }
        }

        return $bestShippingRule;
    }

    /**
     * @param float $baseRate
     * @param float $weightRate
     * @param float $weightKg
     * @return float
     */
    protected function calculate($baseRate, $weightRate, $weightKg)
    {
        return round($baseRate + $weightRate * $weightKg, 4);
    }
}
