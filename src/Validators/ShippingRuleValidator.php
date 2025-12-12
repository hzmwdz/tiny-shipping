<?php

namespace Hzmwdz\TinyShipping\Validators;

use Illuminate\Support\Facades\Validator;

class ShippingRuleValidator
{
    /**
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validate($data)
    {
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'base_rate' => 'required|numeric|min:0',
            'weight_rate' => 'required|numeric|min:0',
            'shipping_regions' => 'required|array',
            'shipping_regions.*.region_level_1_id' => 'required|integer|min:0',
            'shipping_regions.*.region_level_1_name' => 'required|string|max:255',
            'shipping_regions.*.region_level_2_id' => 'nullable|integer|min:0',
            'shipping_regions.*.region_level_2_name' => 'nullable|string|max:255',
            'shipping_regions.*.region_level_3_id' => 'nullable|integer|min:0',
            'shipping_regions.*.region_level_3_name' => 'nullable|string|max:255',
        ])->validate();
    }
}
