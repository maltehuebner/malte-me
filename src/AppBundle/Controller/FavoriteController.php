<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Favorite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FavoriteController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function favoriteAction(Request $request, UserInterface $user, string $photoId): Response
    {
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        $favorite = new Favorite();
        $favorite
            ->setPhoto($photo)
            ->setUser($user)
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($favorite);
        $em->flush();

        return $this->redirectToRoute(
            'show_photo',
            [
                'slug' => $photo->getSlug()
            ]
        );
    }
}
