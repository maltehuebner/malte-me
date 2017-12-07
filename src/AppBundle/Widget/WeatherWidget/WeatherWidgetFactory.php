<?php

namespace AppBundle\Widget\LuftWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;
use Cmfcmf\OpenWeatherMap;

class WeatherWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $cities = $this->doctrine->getRepository(City::class)->findAll();

        /** @var City $city */
        foreach ($cities as $city) {
            $luftData = $this->fetchWeather($city);

            $luftModel = $this->createWeatherModel($city, $luftData);

            $this->cacheData($luftModel);
        }

        return $this;
    }

    protected function fetchWeather(City $city): array
    {
        $lang = 'de';
        $units = 'metric';

        $latLng = [
            'lat' => $city->getLatitude(),
            'lng' => $city->getLongitude(),
        ];

        $owm = new OpenWeatherMap('');

        $weather = $owm->getWeather($latLng, $units, $lang);

        var_dump($weather);

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
