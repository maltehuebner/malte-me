<?php
/**
 * Created by PhpStorm.
 * User: maltehuebner
 * Date: 04.08.17
 * Time: 22:54
 */

namespace AppBundle\Controller;


use AppBundle\Entity\City;
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
        return $this->get('session')->get('city');
    }
}