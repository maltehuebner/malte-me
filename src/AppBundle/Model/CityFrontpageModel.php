<?php

namespace AppBundle\Model;

use AppBundle\Entity\City;

class CityFrontpageModel
{
    /** @var City $city */
    protected $city;

    /** @var string $frontpageUrl */
    protected $frontpageUrl;

    public function __construct(City $city, string $frontpageUrl)
    {
        $this->city = $city;
        $this->frontpageUrl = $frontpageUrl;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function getFrontpageUrl(): string
    {
        return $this->frontpageUrl;
    }
}