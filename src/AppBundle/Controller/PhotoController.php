<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use \Malenki\Slug;

class PhotoController extends Controller
{
    public function uploadAction(Request $request, UserInterface $user)
    {
        $photo = new Photo();

        $uploadForm = $this->createForm(PhotoType::class, $photo);

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Photo $photo */
            $photo = $uploadForm->getData();

            $photo->setUser($user);
            $em->persist($photo); // first persist to generate id
            $em->flush();

            $slug = new Slug($photo->getTitle().' '.$photo->getId());
            $photo->setSlug($slug);

            $em->persist($photo); // second persist to save slug
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
