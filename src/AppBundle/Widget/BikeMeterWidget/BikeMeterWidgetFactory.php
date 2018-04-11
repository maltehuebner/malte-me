<?php declare(strict_types=1);

namespace AppBundle\Widget\BikeMeterWidget;

use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;

class BikeMeterWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $this->cacheData(new BikeMeterDataModel());

        return $this;
    }
}
