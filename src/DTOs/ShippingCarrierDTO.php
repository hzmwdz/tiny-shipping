<?php

namespace Hzmwdz\TinyShipping\DTOs;

class ShippingCarrierDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $code;

    /**
     * @var float
     */
    public $baseRate;

    /**
     * @var float
     */
    public $weightRate;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->code = $data['code'];
        $this->baseRate = $data['base_rate'];
        $this->weightRate = $data['weight_rate'];
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray($data)
    {
        return new self($data);
    }
}
