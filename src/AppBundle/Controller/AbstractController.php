<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Seo\SeoPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

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

    protected function getCity(Request $request = null): ?City
    {
        $cityId = $this->get('session')->get('cityId');

        /** @var City $city */
        $city = $this->getDoctrine()->getRepository(City::class)->find($cityId);

        if ($request && $this->isDefaultHostname($request) && !$city) {
            return null;
        } elseif (!$city) {
            throw $this->createNotFoundException('City not found');
        }

        return $city;
    }

    protected function getSeoPage(): SeoPage
    {
        return $this->get('app.seo_page');
    }

    protected function isDefaultHostname(Request $request): boolean
    {
        $defaultHostname = $this->getParameter('default_hostname');

        return $defaultHostname === $request->getHost();
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