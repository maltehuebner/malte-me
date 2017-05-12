<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class PhotoController extends Controller
{
    public function viewAction(Request $request, UserInterface $user = null, string $slug): Response
    {
        /** @var Photo $photo */
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

    public function editAction(Request $request, UserInterface $user = null, int $photoId): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        $editForm = $this->createForm(PhotoEditType::class, $photo);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Photo $photo */
            $photo = $editForm->getData();

            $em->flush();

            return $this->redirectToRoute('show_photo', ['slug' => $photo->getSlug()]);
        }

        return $this->render('AppBundle:Photo:edit.html.twig', [
            'photo' => $photo,
            'editForm' => $editForm->createView(),
        ]);
    }
}
