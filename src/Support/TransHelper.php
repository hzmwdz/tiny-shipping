<?php

namespace Hzmwdz\TinyShipping\Support;

use Illuminate\Support\Facades\Lang;

class TransHelper
{
    public const NAME = 'tiny-shipping';

    /**
     * @param int $id
     * @return string
     */
    public static function shippingCarrierNotFound($id)
    {
        $key = self::NAME . "::messages.shipping_carrier_not_found";

        return Lang::get($key, ['id' => $id]);
    }

    /**
     * @param int $id
     * @return string
     */
    public static function shippingRuleNotFound($id)
    {
        $key = self::NAME . "::messages.shipping_rule_not_found";

        return Lang::get($key, ['id' => $id]);
    }
}
