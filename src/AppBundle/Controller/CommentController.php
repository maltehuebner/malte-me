<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CommentController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @ParamConverter("photo", class="AppBundle:Photo")
     */
    public function addAction(Request $request, UserInterface $user, Photo $photo): Response
    {
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

        return $this->redirectToRoute('show_photo', [
            'photoSlug' => $photo->getSlug()
        ]);
    }
}
