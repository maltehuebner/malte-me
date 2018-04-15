<?php declare(strict_types=1);

namespace AppBundle\Widget\WeatherWidget;

use AppBundle\Entity\WeatherData;
use AppBundle\Widget\WidgetDataInterface;

class WeatherModel extends WeatherData implements WidgetDataInterface
{
    public function getIdentifier(): string
    {
        return sprintf('weather-%s', $this->city->getSlug());
    }
}
