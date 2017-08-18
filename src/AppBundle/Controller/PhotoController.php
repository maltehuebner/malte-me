<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoEditType;
use AppBundle\Form\Type\PhotoLocateType;
use Malenki\Slug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class PhotoController extends AbstractController
{
    public function embedAction(Request $request, int $id): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($id);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        return $this->render('AppBundle:Photo:embed.html.twig', [
            'photo' => $photo,
        ]);
    }

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

        $this->getSeoPage()
            ->setTitle($photo->getTitle())
            ->setDescription($photo->getDescription())
            ->setPreviewPhoto($photo)
        ;

        return $this->render('AppBundle:Photo:view.html.twig', [
            'photo' => $photo,
            'previousPhoto' => $this->getDoctrine()->getRepository(Photo::class )->findPreviousPhoto($photo),
            'nextPhoto' => $this->getDoctrine()->getRepository(Photo::class )->findNextPhoto($photo),
            'comments' => $comments,
            'userFavorites' => $userFavorites,
        ]);
    }

    public function editAction(Request $request, UserInterface $user, int $photoId): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        if ($photo->getUser() !== $user && !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $editForm = $this->createForm(PhotoEditType::class, $photo);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Photo $photo */
            $photo = $editForm->getData();

            if ($photo->getImported()) {
                $photo
                    ->setSlug(new Slug($photo->getTitle().' '.$photo->getId()))
                    ->setEnabled(true)
                ;
            }

            $em->flush();

            return $this->redirectToRoute('show_photo', ['slug' => $photo->getSlug()]);
        }

        return $this->render('AppBundle:Photo:edit.html.twig', [
            'photo' => $photo,
            'editForm' => $editForm->createView(),
        ]);
    }

    public function locateAction(Request $request, UserInterface $user, int $photoId): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        if ($photo->getUser() !== $user && !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $editForm = $this->createForm(PhotoLocateType::class, $photo);

        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Photo $photo */
            $photo = $editForm->getData();

            $em->flush();
        }

        return $this->redirectToRoute('show_photo', ['slug' => $photo->getSlug()]);
    }
}
