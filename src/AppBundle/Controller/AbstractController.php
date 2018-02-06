<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Seo\SeoPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

class AbstractController extends Controller
{
    protected function getCityByHostname(string $hostname): City
    {
        $hostname = str_replace('www.', '', $hostname);

        $city = $this->getDoctrine()->getRepository(City::class)->findOneByHostname($hostname);

        if (!$city) {
            throw $this->createNotFoundException(sprintf('Hostname %s not found', $hostname));
        }

        return $city;
    }

    protected function getCityBySlug(string $citySlug): City
    {
        $city = $this->getDoctrine()->getRepository(City::class)->findOneBySlug($citySlug);

        if (!$city) {
            throw $this->createNotFoundException(sprintf('City %s not found', $citySlug));
        }

        return $city;
    }

    protected function getCity(Request $request = null): ?City
    {
        if (!$request || !$this->get('session')->has('cityId')) {
            return null;
        }

        /** @var int $cityId */
        $cityId = $this->get('session')->get('cityId');

        /** @var City $city */
        $city = $this->getDoctrine()->getRepository(City::class)->find($cityId);


        if (!$city) {
            throw $this->createNotFoundException('City not found');
        }

        return $city;
    }

    protected function getSeoPage(): SeoPage
    {
        return $this->get('app.seo_page');
    }

    protected function generateRouteForCity(City $city, string $route, array $routeParams = []): string
    {
        /** @var RequestContext $context */
        $context = $this->get('router')->getContext();

        $context->setHost($city->getHostname());

        if ($this->container->getParameter('kernel.environment') === 'dev') {
            $context->setScheme('http');
        } else {
            $context->setScheme('https');
        }

        return $this->generateUrl($route, $routeParams, RouterInterface::ABSOLUTE_URL);
    }
}
