<?php declare(strict_types=1);

namespace AppBundle\Widget\BikeMeterWidget;

use AppBundle\Entity\BikeMeter;
use AppBundle\Entity\BikeMeterData;
use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;

class BikeMeterWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $bikeMeter = $this->doctrine->getRepository(BikeMeter::class)->find(1);

        $model = new BikeMeterDataModel();

        $today = new \DateTime('now', new \DateTimeZone('Europe/Berlin'));
        $model->setTodaySum($this->doctrine->getRepository(BikeMeterData::class)->countForDay($bikeMeter, $today));

        $yesterday = $today->sub(new \DateInterval('P1D'));
        $model->setYesterdaySum($this->doctrine->getRepository(BikeMeterData::class)->countForDay($bikeMeter, $yesterday));

        $this->cacheData($model);

        return $this;
    }
}
