<?php

namespace Hzmwdz\TinyShipping\Contracts;

interface CreateShipping
{
    /**
     * @param array $data
     * @return \Hzmwdz\TinyShipping\Models\Shipping
     */
    public function execute($data);
}
