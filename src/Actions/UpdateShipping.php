<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\Contracts\UpdateShipping as UpdateShippingContract;
use Hzmwdz\TinyShipping\Models\Shipping;
use Hzmwdz\TinyShipping\Support\CacheKeyHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Hzmwdz\TinyShipping\Validators\UpdateShippingValidator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UpdateShipping implements UpdateShippingContract
{
    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function execute($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $validated = UpdateShippingValidator::validate($data);

            $shipping = $this->findShipping($id);

            $this->ensureShippingNotExists($shipping->id, $validated['name']);

            $shipping->update($validated);

            $this->syncShippingRules($shipping, $validated['rules'] ?? []);

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
     * @param int $id
     * @param string $name
     * @return void
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function ensureShippingNotExists($id, $name)
    {
        if (Shipping::where('name', $name)->where('id', '!=', $id)->exists()) {
            throw new BusinessException(
                TransHelper::shippingAlreadyExists($name)
            );
        }
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\Shipping $shipping
     * @param array $validatedRules
     * @return void
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function syncShippingRules($shipping, $validatedRules)
    {
        $existingIds = $shipping->rules()->pluck('id')->flip()->all();

        foreach ($validatedRules as $validatedRule) {
            $payload = Arr::except($validatedRule, ['id']);

            if (!empty($validatedRule['id'])) {
                $ruleId = $validatedRule['id'];

                if (!isset($existingIds[$ruleId])) {
                    throw new BusinessException(
                        TransHelper::shippingRuleNotFound($ruleId)
                    );
                }

                $shipping->rules()->whereKey($ruleId)->update($payload);

                unset($existingIds[$ruleId]);
            } else {
                $shipping->rules()->create($payload);
            }
        }

        if (!empty($existingIds)) {
            $shipping->rules()->whereIn('id', array_keys($existingIds))->delete();
        }
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
