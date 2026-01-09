<?php

namespace Hzmwdz\TinyShipping\Contracts;

interface UpdateShipping
{
    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function execute($id, $data);
}
