<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="frontpage")
     */
    public function indexAction(Request $request): Response
    {
        $photoList = $this->getDoctrine()->getRepository('AppBundle:Photo')->findForFrontpage();

        return $this->render('AppBundle:Default:index.html.twig', [
            'photoList' => $photoList
        ]);
    }

    /**
     * @Route("/{slug}", name="show_photo")
     */
    public function showAction(Request $request, string $slug): Response
    {
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->findOneBySlug($slug);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        return $this->render('AppBundle:Default:index.html.twig', [
            'photoList' => [$photo]
        ]);
    }
}
