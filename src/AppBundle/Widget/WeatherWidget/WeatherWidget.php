<?php

namespace AppBundle\Widget\WeatherWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidget;
use AppBundle\Widget\WidgetDataInterface;

class WeatherWidget extends AbstractWidget
{
    /** @var City $city */
    protected $city;

    public function setCity(City $city): WeatherWidget
    {
        $this->city = $city;

        return $this;
    }

    public function render(): WidgetDataInterface
    {
        $identifier = sprintf('weather-%s', $this->city->getSlug());

        return $this->retrieveData($identifier);
    }
}
