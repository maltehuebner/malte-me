<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="frontpage")
     */
    public function indexAction(Request $request)
    {
        $photoList = $this->getDoctrine()->getRepository('AppBundle:Photo')->findForFrontpage();

        return $this->render('AppBundle:Default:index.html.twig', [
            'photoList' => $photoList
        ]);
    }

    /**
     * @Route("/photo/{photoId}", name="show_photo")
     */
    public function showAction(Request $request, int $photoId)
    {
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        return $this->render('AppBundle:Default:index.html.twig', [
            'photoList' => [$photo]
        ]);
    }
}
