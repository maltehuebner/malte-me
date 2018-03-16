<?php

namespace AppBundle\Widget\LuftWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;
use Curl\Curl;

class LuftWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $cities = $this->doctrine->getRepository(City::class)->findAll();

        /** @var City $city */
        foreach ($cities as $city) {
            if ($city->getLatitude() && $city->getLongitude()) {
                $luftData = $this->fetchLuft($city);

                $luftModel = $this->createLuftModel($city, $luftData);

                $this->cacheData($luftModel);
            }
        }

        return $this;
    }

    protected function fetchLuft(City $city): array
    {
        $curl = new Curl();

        $apiUrl = sprintf('https://luft.jetzt/api/?latitude=%f&longitude=%f', $city->getLatitude(), $city->getLongitude());

        $curl->get($apiUrl);

        return $curl->response;
    }

    protected function createLuftModel(City $city, array $luftData): LuftModel
    {
        $luftModel = new LuftModel();
        $luftModel->setCity($city);

        foreach ($luftData as $data) {
            $dateTime = new \DateTime(sprintf('@%d', $data->data->date_time));

            $luftModel->addData(new LuftDataModel(
                $dateTime,
                $data->station->station_code,
                $data->station->title,
                $data->pollutant->name,
                $data->pollutant->unit_plain,
                $data->pollution_level,
                $data->data->value
            ));
        }

        return $luftModel;
    }
}
