<?php

namespace Hzmwdz\TinyShipping\DTOs;

class CalculateShippingDTO
{
    /**
     * @var int
     */
    public $shippingCarrierId;

    /**
     * @var float
     */
    public $weightKg;

    /**
     * @var int
     */
    public $regionLevel1Id;

    /**
     * @var int
     */
    public $regionLevel2Id;

    /**
     * @var int
     */
    public $regionLevel3Id;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->shippingCarrierId = $data['shipping_carrier_id'];
        $this->weightKg = $data['weight_kg'];
        $this->regionLevel1Id = $data['region_level_1_id'];
        $this->regionLevel2Id = $data['region_level_2_id'];
        $this->regionLevel3Id = $data['region_level_3_id'];
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
