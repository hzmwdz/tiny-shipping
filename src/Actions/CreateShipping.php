<?php

namespace Hzmwdz\TinyShipping\Actions;

use Hzmwdz\TinyCore\Exceptions\BusinessException;
use Hzmwdz\TinyShipping\Contracts\CreateShipping as CreateShippingContract;
use Hzmwdz\TinyShipping\Models\Shipping;
use Hzmwdz\TinyShipping\Support\CacheKeyHelper;
use Hzmwdz\TinyShipping\Support\TransHelper;
use Hzmwdz\TinyShipping\Validators\CreateShippingValidator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CreateShipping implements CreateShippingContract
{
    /**
     * @param array $data
     * @return \Hzmwdz\TinyShipping\Models\Shipping
     */
    public function execute($data)
    {
        return DB::transaction(function () use ($data) {
            $validated = CreateShippingValidator::validate($data);

            $this->ensureShippingNotExists($validated['name']);

            $shipping = Shipping::create($validated);

            $this->createShippingRules($shipping, $validated['rules'] ?? []);

            $this->clearCache();

            return $shipping;
        });
    }

    /**
     * @param string $name
     * @return void
     * @throws \Hzmwdz\TinyCore\Exceptions\BusinessException
     */
    protected function ensureShippingNotExists($name)
    {
        if (Shipping::where('name', $name)->exists()) {
            throw new BusinessException(
                TransHelper::shippingAlreadyExists($name)
            );
        }
    }

    /**
     * @param \Hzmwdz\TinyShipping\Models\Shipping $shipping
     * @param array $validatedRules
     * @return void
     */
    protected function createShippingRules($shipping, $validatedRules)
    {
        foreach ($validatedRules as $validatedRule) {
            $shipping->rules()->create($validatedRule);
        }
    }

    /**
     * @return void
     */
    protected function clearCache()
    {
        Cache::forget(CacheKeyHelper::shippingList());
    }
}
