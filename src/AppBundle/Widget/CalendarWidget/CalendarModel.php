<?php

namespace AppBundle\Widget\CriticalmassWidget;

use AppBundle\Widget\WidgetDataInterface;

class CalendarModel implements WidgetDataInterface
{
    protected $eventList = [];

    public function addEvent(CalendarEventModel $eventModel): CalendarModel
    {
        $this->eventList[] = $eventModel;

        return $this;
    }

    public function getEventList(): array
    {
        return $this->eventList;
    }

    public function getIdentifier(): string
    {
        return 'calendar';
    }
}
