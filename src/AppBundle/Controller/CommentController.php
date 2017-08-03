<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommentController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(Request $request, UserInterface $user, int $photoId): Response
    {
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        $message = $request->request->get('message');

        if (!$message) {
            throw $this->createAccessDeniedException();
        }
        
        $comment = new Comment();

        $comment
            ->setUser($user)
            ->setPhoto($photo)
            ->setMessage($message)
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute(
            'show_photo', [
                'slug' => $photo->getSlug()
            ]
        );
    }
}
