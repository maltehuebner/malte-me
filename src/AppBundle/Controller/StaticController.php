<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends AbstractController
{
    public function missionAction(Request $request): Response
    {
        if (!$this->getCity()->getShowMenuMission() || !$this->getCity()->getMissionText()) {
            throw $this->createNotFoundException();
        }
        
        return $this->render('AppBundle:Static:mission.html.twig');
    }
}
