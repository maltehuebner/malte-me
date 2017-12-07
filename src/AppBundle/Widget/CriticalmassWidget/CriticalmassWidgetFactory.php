<?php

namespace AppBundle\Widget\CriticalmassWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;
use Curl\Curl;

class CriticalmassWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $cities = $this->doctrine->getRepository(City::class)->findCitiesWithCriticalmass();

        /** @var City $city */
        foreach ($cities as $city) {
            $criticalmass = $this->fetchCriticalmassData($city);
            $this->cacheData($criticalmass);
        }

        return $this;
    }

    protected function fetchCriticalmassData(City $city): CriticalmassModel
    {
        $citySlug = $city->getCriticalmassCitySlug();

        $curl = new Curl();

        $curl->get(sprintf('https://criticalmass.in/api/%s/current', $citySlug));

        $criticalmass = new CriticalmassModel($citySlug, new \DateTime(sprintf('@%s', $curl->response->dateTime)), $curl->response->location);

        return $criticalmass;
    }
}
