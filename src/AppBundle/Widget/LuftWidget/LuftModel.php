<?php

namespace AppBundle\Widget\LuftWidget;

use AppBundle\Widget\WidgetDataInterface;

class LuftModel implements WidgetDataInterface
{
    protected $eventList = [];

    public function addEvent(LuftDataModel $eventModel): LuftModel
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
