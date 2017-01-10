<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class PhotoController extends Controller
{
    /**
     * @Route("/upload", name="photo_upload")
     */
    public function uploadAction(Request $request, UserInterface $user)
    {
        $photo = new Photo();

        $uploadForm = $this->createForm(PhotoType::class, $photo);

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            /** @var Photo $photo */
            $photo = $uploadForm->getData();
            $photo->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
        }

        return $this->render(
            'AppBundle:Photo:upload.html.twig',
            [
                'uploadForm' => $uploadForm->createView()
            ]
        );
    }
}
