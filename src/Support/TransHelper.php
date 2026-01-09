<?php

namespace Hzmwdz\TinyShipping\Support;

use Illuminate\Support\Facades\Lang;

class TransHelper
{
    /**
     * @var string
     */
    protected static $name = 'tiny-shipping';

    /**
     * @param string $name
     * @return string
     */
    public static function shippingAlreadyExists($name)
    {
        $key = static::$name . '::messages.shipping_already_exists';

        return Lang::get($key, ['name' => $name]);
    }

    /**
     * @param int $id
     * @return string
     */
    public static function shippingNotFound($id)
    {
        $key = static::$name . '::messages.shipping_not_found';

        return Lang::get($key, ['id' => $id]);
    }

    /**
     * @param int $id
     * @return string
     */
    public static function shippingRuleNotFound($id)
    {
        $key = static::$name . '::messages.shipping_rule_not_found';

        return Lang::get($key, ['id' => $id]);
    }
}
