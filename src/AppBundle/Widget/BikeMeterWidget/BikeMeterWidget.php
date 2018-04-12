<?php declare(strict_types=1);

namespace AppBundle\Widget\BikeMeterWidget;

use AppBundle\Widget\AbstractWidget;
use AppBundle\Widget\WidgetDataInterface;

class BikeMeterWidget extends AbstractWidget
{
    public function render(): WidgetDataInterface
    {
        $identifier = 'bike_meter';

        return $this->retrieveData($identifier);
    }
}
