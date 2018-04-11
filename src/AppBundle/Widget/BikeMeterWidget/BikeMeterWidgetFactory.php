<?php declare(strict_types=1);

namespace AppBundle\Widget\BikeMeterWidget;

use AppBundle\Entity\BikeMeterData;
use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;

class BikeMeterWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $model = new BikeMeterDataModel();

        $today = new \DateTime('now', new \DateTimeZone('Europe/Berlin'));
        $model->setTodaySum($this->doctrine->getRepository(BikeMeterData::class)->countForDay($today));

        $yesterday = $today->sub(new \DateInterval('P1D'));
        $model->setYesterdaySum($this->doctrine->getRepository(BikeMeterData::class)->countForDay($yesterday));

        $this->cacheData($model);

        return $this;
    }
}
