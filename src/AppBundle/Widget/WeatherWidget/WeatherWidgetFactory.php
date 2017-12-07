<?php

namespace AppBundle\Widget\WeatherWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\CurrentWeather;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class WeatherWidgetFactory extends AbstractWidgetFactory
{
    protected $openweathermapApiKey;

    public function __construct(Doctrine $doctrine, string $openweathermapApiKey)
    {
        $this->openweathermapApiKey = $openweathermapApiKey;

        parent::__construct($doctrine);
    }

    public function prepare(): WidgetFactoryInterface
    {
        $cities = $this->doctrine->getRepository(City::class)->findAll();

        /** @var City $city */
        foreach ($cities as $city) {
            if (!$city->getLatitude() || !$city->getLongitude()) {
                continue;
            }

            $weather = $this->fetchWeather($city);
            $weatherModel = $this->createWeatherModel($city, $weather);
            $this->cacheData($weatherModel);
        }

        return $this;
    }

    protected function fetchWeather(City $city): CurrentWeather
    {
        $lang = 'de';
        $units = 'metric';

        $latLng = [
            'lat' => $city->getLatitude(),
            'lon' => $city->getLongitude(),
        ];

        $weather = $this->getOpenWeatherMap()->getWeather($latLng, $units, $lang);

        return $weather;
    }

    protected function createWeatherModel(City $city, CurrentWeather $currentWeather): WeatherModel
    {
        $weatherModel = new WeatherModel();

        $weatherModel
            ->setCity($city)
            ->setTemperaturMin($currentWeather->temperature->min->getValue())
            ->setTemperaturMin($currentWeather->temperature->max->getValue())
            ->setClounds($currentWeather->clouds->getValue())
            ->setWindDirection($currentWeather->wind->direction->getValue())
            ->setWindSpeed($currentWeather->wind->speed->getValue())
            ->setWeather($currentWeather->weather->description)
        ;

        return $weatherModel;
    }

    protected function getOpenWeatherMap(): OpenWeatherMap
    {
        return new OpenWeatherMap($this->openweathermapApiKey);
    }
}
