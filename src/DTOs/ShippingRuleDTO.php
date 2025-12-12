<?php

namespace Hzmwdz\TinyShipping\DTOs;

use Illuminate\Support\Collection;

class ShippingRuleDTO
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var float
     */
    public $baseRate;

    /**
     * @var float
     */
    public $weightRate;

    /**
     * @var \Illuminate\Support\Collection
     */
    public $shippingRegions;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->baseRate = $data['base_rate'];
        $this->weightRate = $data['weight_rate'];
        $this->shippingRegions = Collection::make($data['shipping_regions'])->map(function ($regionData) {
            return ShippingRegionDTO::fromArray($regionData);
        });
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
