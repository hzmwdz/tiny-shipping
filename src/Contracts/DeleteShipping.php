<?php

namespace Hzmwdz\TinyShipping\Contracts;

interface DeleteShipping
{
    /**
     * @param int $id
     * @return bool
     */
    public function execute($id);
}
