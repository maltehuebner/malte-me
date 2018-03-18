<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use AppBundle\PhotoUploader\PhotoUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class InvitationController extends AbstractController
{
    public function invitationAction(Request $request, UserInterface $user = null, PhotoUploader $photoUploader, string $code): Response
    {
        /** @var Invitation $invitation */
        $invitation = $this->getDoctrine()->getRepository('AppBundle:Invitation')->findOneByCode($code);

        if (!$invitation || $invitation->getPhoto()) {
            throw $this->createNotFoundException();
        }

        $photo = new Photo();

        $photo
            ->setTitle($invitation->getProposedTitle())
            ->setDescription($invitation->getProposedDescription())
            ->setInvitation($invitation)
        ;

        $uploadForm = $this->createForm(PhotoType::class, $photo);

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $photo = $uploadForm->getData();

            $photo = $photoUploader->handleUpload($photo, $user);

            if ($photo && $user) {
                return $this->redirectToRoute(
                    'show_photo', [
                    'photoSlug' => $photo->getSlug()
                ]);
            } elseif ($photo) {
                return $this->anonymousLogin($photo);
            }
        }

        return $this->render('AppBundle:Invitation:invitation.html.twig', [
            'uploadForm' => $uploadForm->createView(),
            'invitation' => $invitation,
        ]);
    }

    protected function anonymousLogin(Photo $photo): Response
    {
        $session = new Session();
        $session->set('uploaded_photo_id', $photo->getId());

        $csrfToken = $this
            ->get('security.csrf.token_manager')
            ->getToken('authenticate')
            ->getValue()
        ;

        return $this->render('AppBundle:Invitation:login.html.twig',
            [
                'csrf_token' => $csrfToken,
                'last_username' => null,
                'error' => null,
            ]);
    }
}
