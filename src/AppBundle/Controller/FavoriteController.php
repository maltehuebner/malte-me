<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FavoriteController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @ParamConverter("photo", class="AppBundle:Photo")
     */
    public function favoriteAction(UserInterface $user, Photo $photo): Response
    {
        $em = $this->getDoctrine()->getManager();

        $favorite = $this->getDoctrine()->getRepository('AppBundle:Favorite')->findForUserAndPhoto($user, $photo);

        if (!$favorite) {
            $favorite = new Favorite();
            $favorite
                ->setPhoto($photo)
                ->setUser($user);

            $em->persist($favorite);
        } else {
            $em->remove($favorite);
        }

        $em->flush();

        return $this->redirectToRoute(
            'show_photo',
            [
                'slug' => $photo->getSlug()
            ]
        );
    }
}
