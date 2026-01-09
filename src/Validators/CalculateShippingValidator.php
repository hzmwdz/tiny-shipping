<?php

namespace Hzmwdz\TinyShipping\Validators;

use Illuminate\Support\Facades\Validator;

class CalculateShippingValidator
{
    /**
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validate($data)
    {
        return Validator::make($data, [
            'shipping_id' => 'required|integer|gt:0',
            'weight_kg' => 'required|numeric|min:0',
            'region_level1_id' => 'required|integer|min:0',
            'region_level2_id' => 'required|integer|min:0',
            'region_level3_id' => 'required|integer|min:0',
        ])->validate();
    }
}
