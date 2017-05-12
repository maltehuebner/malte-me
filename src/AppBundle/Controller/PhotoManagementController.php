<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use Gregwar\Image\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class PhotoManagementController extends Controller
{
    public function rotateAction(Request $request, UserInterface $user, int $photoId): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        if ($photo->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($photo, 'imageFile');

        $photoDirectory = $this->getParameter('upload.photo_path');

        $filename = $photoDirectory.$path;

        Image::open($filename)
            ->rotate(90)
            ->save($filename);

        return $this->redirectToRoute('show_photo', [
            'slug' => $photo->getSlug()
        ]);
    }
}
