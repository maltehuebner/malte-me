<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends Controller
{
    public function missionAction(Request $request): Response
    {
        return $this->render('AppBundle:Static:mission.html.twig');
    }
}
