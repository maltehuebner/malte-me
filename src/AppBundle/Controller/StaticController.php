<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends AbstractController
{
    public function missionAction(Request $request): Response
    {
        $this->getSeoPage()
            ->setTitle('Mission')
            ->setDescription('Hamburg wird Fahrradstadt und wir fahren schon mal los! Darum geht’s bei Fahrradstadt.Hamburg')
        ;

        return $this->render('AppBundle:Static:mission.html.twig');
    }
}
