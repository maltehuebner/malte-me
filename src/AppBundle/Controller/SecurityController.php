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

        $refererParts = parse_url($referer);

        $city = $this->getCityByHostname($refererParts['host']);

        $url = $this->generateRouteForCity($city, 'frontpage');

        return new RedirectResponse($url);
    }
}