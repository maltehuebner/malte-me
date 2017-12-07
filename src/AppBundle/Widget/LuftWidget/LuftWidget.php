<?php

namespace AppBundle\Widget\LuftWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidget;
use AppBundle\Widget\WidgetDataInterface;

class LuftWidget extends AbstractWidget
{
    public function render(): WidgetDataInterface
    {
        $identifier = 'luft';

        return $this->retrieveData($identifier);
    }
}
