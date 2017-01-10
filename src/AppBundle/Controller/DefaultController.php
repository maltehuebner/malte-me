<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $photoList = $this->getDoctrine()->getRepository('AppBundle:Photo')->findAll();

        return $this->render('AppBundle:Default:index.html.twig', [
            'photoList' => $photoList
        ]);
    }
}
