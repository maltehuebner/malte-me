<?php declare(strict_types=1);

namespace AppBundle\Widget\WeatherWidget;

use AppBundle\Entity\City;
use AppBundle\Entity\WeatherData;
use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;

class WeatherWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $cities = $this->doctrine->getRepository(City::class)->findAll();

        /** @var City $city */
        foreach ($cities as $city) {
            if (!$city->getLatitude() || !$city->getLongitude()) {
                continue;
            }

            $weatherData = $this->doctrine->getRepository(WeatherData::class)->findForCityDay($city);

            $this->cacheData($weatherData);
        }

        return $this;
    }
}
