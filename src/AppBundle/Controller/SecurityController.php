<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    public function targetAction(Request $request): Response
    {
        $referer = $request->headers->get('referer');

        $hostname = parse_url($referer, PHP_URL_HOST);

        $city = $this->getCityByHostname($hostname);

        $url = $this->generateRouteForCity($city, 'frontpage');

        return new RedirectResponse($url);
    }
}