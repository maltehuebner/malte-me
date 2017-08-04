<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends AbstractController
{
    public function missionAction(Request $request): Response
    {
        return $this->render('AppBundle:Static:mission.html.twig');
    }
}
