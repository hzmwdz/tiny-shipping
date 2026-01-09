<?php

namespace Hzmwdz\TinyShipping\Contracts;

interface CalculateShipping
{
    /**
     * @param array $data
     * @return float
     */
    public function execute($data);
}
