<?php

namespace Hzmwdz\TinyShipping\Support;

use Illuminate\Support\Facades\Config;

class CacheHelper
{
    public const NAME = 'tiny-shipping';

    /**
     * @return string
     */
    public static function prefix()
    {
        return Config::get(self::NAME . '.cache_prefix');
    }

    /**
     * @return string
     */
    public static function ttl()
    {
        return Config::get(self::NAME . '.cache_ttl');
    }

    /**
     * @return string
     */
    public static function keyForAllShippingCarriers()
    {
        $prefix = self::prefix();

        return "{$prefix}.shipping_carrier.all";
    }

    /**
     * @param int $id
     * @return string
     */
    public static function keyForShippingCarrier($id)
    {
        $prefix = self::prefix();

        return "{$prefix}.shipping_carrier.{$id}";
    }
}
