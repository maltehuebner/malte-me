<?php

namespace AppBundle\Controller;

use Curl\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    public function listAction(Request $request): Response
    {
        $curl = new Curl();
        $curl->get($this->getParameter('cyclingcities.api.event_list'));

        $eventList = json_decode($curl->response);

        return $this->render('AppBundle:Event:list.html.twig', [
            'eventList' => $eventList
        ]);
    }
}
