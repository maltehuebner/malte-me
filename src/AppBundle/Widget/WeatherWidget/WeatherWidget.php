<?php

namespace AppBundle\Widget\WeatherWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidget;
use AppBundle\Widget\WidgetDataInterface;

class WeatherWidget extends AbstractWidget
{
    public function render(): WidgetDataInterface
    {
        $identifier = 'luft';

        return $this->retrieveData($identifier);
    }
}
