<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\Contracts\CalculateShipping as CalculateShippingContract;
use Hzmwdz\TinyShipping\Models\Shipping;
use Hzmwdz\TinyShipping\Support\CacheKeyHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Hzmwdz\TinyShipping\Validators\CalculateShippingValidator;
use Illuminate\Support\Facades\Cache;

class CalculateShipping implements CalculateShippingContract
{
    /**
     * @var int
     */
    protected $ttl = 7200;

    /**
     * @param array $data
     * @return float
     */
    public function execute($data)
    {
        $validated = CalculateShippingValidator::validate($data);

        $shipping = $this->findShipping($validated['shipping_id']);

        $rule = $this->matchShippingRule($shipping, $validated);

        if ($rule) {
            return $this->calculateShipping(
                $rule->base_rate,
                $rule->weight_rate,
                $validated['weight_kg']
            );
        }

        return $this->calculateShipping(
            $shipping->base_rate,
            $shipping->weight_rate,
            $validated['weight_kg']
        );
    }

    /**
     * @param int $id
     * @return \Hzmwdz\TinyShipping\Models\Shipping
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function findShipping($id)
    {
        $cacheKey = CacheKeyHelper::shippingItem($id);

        $shipping = Cache::remember($cacheKey, $this->ttl, function () use ($id) {
            return Shipping::with('rules')->find($id);
        });

        if (!$shipping) {
            throw new BusinessException(
                TransHelper::shippingNotFound($id)
            );
        }

        return $shipping;
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\Shipping $shipping
     * @param array $validated
     * @return \Hzmwdz\TinyShipping\Models\ShippingRule|null
     */
    protected function matchShippingRule($shipping, $validated)
    {
        $bestRule = null;

        $bestScore = 0;

        foreach ($shipping->rules as $rule) {
            $currentScore = 0;

            if (isset($rule->region_level3_id)) {
                if ($rule->region_level3_id == $validated['region_level3_id']) {
                    $currentScore = 3;
                }
            } else {
                if (isset($rule->region_level2_id)) {
                    if ($rule->region_level2_id == $validated['region_level2_id']) {
                        $currentScore = 2;
                    }
                } else {
                    if ($rule->region_level1_id == $validated['region_level1_id']) {
                        $currentScore = 1;
                    }
                }
            }

            if ($currentScore > $bestScore) {
                $bestScore = $currentScore;
                $bestRule = $rule;
            }
        }

        return $bestRule;
    }

    /**
     * @param float $baseRate
     * @param float $weightRate
     * @param float $weightKg
     * @return float
     */
    protected function calculateShipping($baseRate, $weightRate, $weightKg)
    {
        return round($baseRate + $weightRate * $weightKg, 4);
    }
}
