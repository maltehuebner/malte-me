<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use AppBundle\PhotoUploader\PhotoUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class InvitationController extends Controller
{
    public function invitationAction(Request $request, UserInterface $user, string $code): Response
    {
        /** @var Invitation $invitation */
        $invitation = $this->getDoctrine()->getRepository('AppBundle:Invitation')->findOneByCode($code);

        if (!$invitation) {
            throw $this->createNotFoundException();
        }

        $photo = new Photo();

        $photo
            ->setTitle($invitation->getProposedTitle())
            ->setDescription($invitation->getProposedDescription())
        ;

        $uploadForm = $this->createForm(PhotoType::class, $photo);

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $photo = $uploadForm->getData();

            /** @var PhotoUploader $photoUploader */
            $photoUploader = $this->get('app.photo_uploader');

            $photo = $photoUploader->handleUpload($photo, $user);

            if ($photo) {
                return $this->redirectToRoute(
                    'show_photo',
                    [
                        'slug' => $photo->getSlug()
                    ]
                );
            }
        }

        return $this->render('AppBundle:Invitation:invitation.html.twig', [
            'uploadForm' => $uploadForm->createView(),
            'invitation' => $invitation,
        ]);
    }
}
