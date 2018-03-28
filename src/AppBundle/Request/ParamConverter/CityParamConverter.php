<?php

namespace AppBundle\Request\ParamConverter;

use AppBundle\Entity\City;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class CityParamConverter extends AbstractParamConverter
{
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $city = null;

        $cityId = $request->get('cityId') ?? $request->getSession()->get('cityId');

        if ($cityId) {
            $city = $this->registry->getRepository(City::class)->find($cityId);
        }

        if (!$city) {
            $citySlug = $request->get('citySlug');

            if ($citySlug) {
                $city = $this->registry->getRepository(City::class)->findOneBySlug($citySlug);
            }
        }

        if ($city) {
            $request->attributes->set($configuration->getName(), $city);
        } else {
            $this->notFound($configuration);
        }
    }
}
