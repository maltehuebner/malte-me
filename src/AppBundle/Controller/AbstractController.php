<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RouterInterface;

class AbstractController extends Controller
{
    protected function generateRouteForCity(RouterInterface $router, City $city, string $route, array $routeParams = []): string
    {
        $context = $router->getContext();
        $context->setHost($city->getHostname());

        if ($this->container->getParameter('kernel.environment') === 'dev') {
            $context->setScheme('http');
        } else {
            $context->setScheme('https');
        }

        return $this->generateUrl($route, $routeParams, RouterInterface::ABSOLUTE_URL);
    }
}
