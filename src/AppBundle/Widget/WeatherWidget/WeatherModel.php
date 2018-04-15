<?php declare(strict_types=1);

namespace AppBundle\Widget\WeatherWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\WidgetDataInterface;

class WeatherModel implements WidgetDataInterface
{
    /**
     * @var City $city
     */
    protected $city;

    public function getIdentifier(): string
    {
        return sprintf('weather-%s', $this->city->getSlug());
    }
}
