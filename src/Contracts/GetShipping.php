<?php

namespace Hzmwdz\TinyShipping\Contracts;

interface GetShipping
{
    /**
     * @param int $id
     * @return \Hzmwdz\TinyShipping\Models\Shipping|null
     */
    public function execute($id);
}
