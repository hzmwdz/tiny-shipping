<?php

namespace Hzmwdz\TinyShipping\DTOs;

class ShippingRegionDTO
{
    /**
     * @var int
     */
    public $regionLevel1Id;

    /**
     * @var string
     */
    public $regionLevel1Name;

    /**
     * @var int|null
     */
    public $regionLevel2Id;

    /**
     * @var string|null
     */
    public $regionLevel2Name;

    /**
     * @var int|null
     */
    public $regionLevel3Id;

    /**
     * @var string|null
     */
    public $regionLevel3Name;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->regionLevel1Id = $data['region_level_1_id'];
        $this->regionLevel1Name = $data['region_level_1_name'];
        $this->regionLevel2Id = $data['region_level_2_id'] ?? null;
        $this->regionLevel2Name = $data['region_level_2_name'] ?? null;
        $this->regionLevel3Id = $data['region_level_3_id'] ?? null;
        $this->regionLevel3Name = $data['region_level_3_name'] ?? null;
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
