<?php

namespace Hzmwdz\TinyShipping\Support;

class CacheKeyHelper
{
    /**
     * @var string
     */
    protected static $prefix = 'tiny_shipping_cache';

    /**
     * @param string $key
     * @return string
     */
    public static function shippingList($key = 'all')
    {
        return static::$prefix . ".shipping.list.{$key}";
    }

    /**
     * @param int|string $key
     * @return string
     */
    public static function shippingItem($key)
    {
        return static::$prefix . ".shipping.item.{$key}";
    }
}
