<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Seo\SeoPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractController extends Controller
{
    /**
     * @deprecated
     */
    protected function getCityBySlug(string $citySlug = null): ?City
    {
        if (!$citySlug) {
            return null;
        }

        $city = $this->getDoctrine()->getRepository(City::class)->findOneBySlug($citySlug);

        if (!$city) {
            throw $this->createNotFoundException(sprintf('City %s not found', $citySlug));
        }

        return $city;
    }

    public function getCity(): City
    {
        $cityId = $this->get('session')->get('cityId');

        /** @var City $city */
        $city = $this->getDoctrine()->getRepository(City::class)->find($cityId);

        return $city;
    }

    protected function getSeoPage(): SeoPage
    {
        return $this->get('app.seo_page');
    }
}