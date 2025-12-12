<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\DTOs\ShippingRegionDTO;
use Hzmwdz\TinyShipping\DTOs\ShippingRuleDTO;
use Hzmwdz\TinyShipping\Models\ShippingRule;
use Hzmwdz\TinyShipping\Support\CacheHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Hzmwdz\TinyShipping\Validators\ShippingRuleValidator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UpdateShippingRuleAction
{
    /**
     * @param int $shippingCarrierId
     * @param int $id
     * @param array $data
     * @return \Hzmwdz\TinyShipping\Models\ShippingRule
     */
    public function execute($shippingCarrierId, $id, $data)
    {
        $validated = ShippingRuleValidator::validate($data);

        $dto = ShippingRuleDTO::fromArray($validated);

        $shippingRule = $this->findShippingRule($shippingCarrierId, $id);

        $shippingRule = $this->updateShippingRule($shippingRule, $dto);

        $this->clearCache($shippingRule);

        return $shippingRule;
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
     * @param \Hzmwdz\TinyShipping\DTOs\ShippingRuleDTO $dto
     * @return \Hzmwdz\TinyShipping\Models\ShippingRule
     */
    protected function updateShippingRule($shippingRule, $dto)
    {
        return DB::transaction(function () use ($shippingRule, $dto) {
            $shippingRule->update([
                'title' => $dto->title,
                'base_rate' => $dto->baseRate,
                'weight_rate' => $dto->weightRate,
            ]);

            $shippingRule->shippingRegions()->delete();

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
