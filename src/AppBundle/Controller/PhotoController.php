<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use PHPExif\Reader\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use \Malenki\Slug;

class PhotoController extends Controller
{
    public function viewAction(Request $request, string $slug): Response
    {
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->findOneBySlug($slug);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        return $this->render('AppBundle:Photo:view.html.twig', [
            'photo' => $photo
        ]);
    }
}
