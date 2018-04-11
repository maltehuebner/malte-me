<?php declare(strict_types=1);

namespace AppBundle\Widget\BikeMeterWidget;

use AppBundle\Widget\WidgetDataInterface;

class BikeMeterDataModel implements WidgetDataInterface
{
    public function getValue(): int
    {
        return 123523;
    }

    public function getIdentifier(): string
    {
        return 'bike_meter';
    }
}
