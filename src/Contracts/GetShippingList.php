<?php

namespace Hzmwdz\TinyShipping\Contracts;

interface GetShippingList
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function execute();
}
