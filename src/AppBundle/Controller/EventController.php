<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    public function listAction(Request $request): Response
    {
        return $this->render('AppBundle:Event:list.html.twig', [
        ]);
    }


}
