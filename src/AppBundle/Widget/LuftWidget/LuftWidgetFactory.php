<?php

namespace AppBundle\Widget\LuftWidget;

use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;
use Curl\Curl;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

class LuftWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $luftData = $this->fetchLuft();

        $luftModel = $this->createLuftModel($luftData);

        $this->cacheData($luftModel);
        
        return $this;
    }

    protected function fetchLuft(): array
    {
        $curl = new Curl();

        $curl->get('https://luft.jetzt/api/?latitude=53.6038583&longitude=9.9061903');

        return $curl->response;
    }

    protected function createLuftModel(array $luftData): LuftModel
    {
        $luftModel = new LuftModel();

        foreach ($luftData as $data) {
            $dateTime = new \DateTime(sprintf('@%d', $data->data->date_time));

            $luftModel->addData(new LuftDataModel($dateTime, $data->pollutant->name, $data->pollutant->unit_plain, $data->data->value, $data->pollution_level));
        }

        return $luftModel;
    }
}
