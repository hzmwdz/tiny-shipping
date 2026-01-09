<?php

namespace Hzmwdz\TinyShipping\Validators;

use Illuminate\Support\Facades\Validator;

class CreateShippingValidator
{
    /**
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validate($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'base_rate' => 'required|numeric|min:0',
            'weight_rate' => 'required|numeric|min:0',
            'rules' => 'sometimes|array|min:1',
            'rules.*.region_level1_id' => 'required_with:rules|integer|gt:0',
            'rules.*.region_level1_name' => 'required_with:rules|string|max:255',
            'rules.*.region_level1_native' => 'required_with:rules|string|max:255',
            'rules.*.region_level2_id' => 'nullable|integer|gt:0',
            'rules.*.region_level2_name' => 'nullable|string|max:255',
            'rules.*.region_level2_native' => 'nullable|string|max:255',
            'rules.*.region_level3_id' => 'nullable|integer|gt:0',
            'rules.*.region_level3_name' => 'nullable|string|max:255',
            'rules.*.region_level3_native' => 'nullable|string|max:255',
            'rules.*.base_rate' => 'required_with:rules|numeric|min:0',
            'rules.*.weight_rate' => 'required_with:rules|numeric|min:0',
        ])->validate();
    }
}
