<?php declare(strict_types=1);

namespace AppBundle\Widget\CalendarWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidget;
use AppBundle\Widget\WidgetDataInterface;

class CalendarWidget extends AbstractWidget
{
    public function render(): WidgetDataInterface
    {
        $identifier = 'calendar';

        return $this->retrieveData($identifier);
    }
}
