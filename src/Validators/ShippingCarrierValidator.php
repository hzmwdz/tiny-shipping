<?php

namespace Hzmwdz\TinyShipping\Validators;

use Illuminate\Support\Facades\Validator;

class ShippingCarrierValidator
{
    /**
     * @param array $data
     * @param int|null $id
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validate($data, $id = null)
    {
        $uniqueName = $id
            ? "unique:shipping_carriers,name,{$id}"
            : "unique:shipping_carriers,name";

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'code' => 'required|string|max:255',
            'base_rate' => 'required|numeric|min:0',
            'weight_rate' => 'required|numeric|min:0',
        ])->validate();
    }
}
