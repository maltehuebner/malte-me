<?php

namespace AppBundle\Widget\LuftWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidget;
use AppBundle\Widget\WidgetDataInterface;

class LuftWidget extends AbstractWidget
{
    /** @var City $city */
    protected $city;

    public function setCity(City $city): LuftWidget
    {
        $this->city = $city;

        return $this;
    }

    public function render(): WidgetDataInterface
    {
        $identifier = sprintf('luft-%s', $this->city->getSlug());

        return $this->retrieveData($identifier);
    }
}
