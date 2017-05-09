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
    public function viewAction(Request $request, UserInterface $user = null, string $slug): Response
    {
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->findOneBySlug($slug);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')->findForPhoto($photo);

        $userFavorites = [];

        if ($user) {
            $star = $this->getDoctrine()->getRepository('AppBundle:Favorite')->findForUserAndPhoto($user, $photo);

            if ($star) {
                $userFavorites[$star->getPhoto()->getId()] = $star;
            }
        }

        return $this->render('AppBundle:Photo:view.html.twig', [
            'photo' => $photo,
            'comments' => $comments,
            'userFavorites' => $userFavorites,
        ]);
    }
}
