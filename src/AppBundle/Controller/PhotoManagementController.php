<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Photo\PhotoManipulatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PhotoManagementController extends AbstractController
{
    /**
     * @ParamConverter("photo", class="AppBundle:Photo")
     */
    public function rotateAction(PhotoManipulatorInterface $photoManipulator, UserInterface $user, Photo $photo): Response
    {
        if ($photo->getUser() !== $user && !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $photoManipulator
            ->open($photo)
            ->rotate(-90)
            ->save();

        return $this->redirectToRoute('show_photo', [
            'photoSlug' => $photo->getSlug()
        ]);
    }

    /**
     * @ParamConverter("photo", class="AppBundle:Photo")
     */
    public function censorAction(Request $request, UserInterface $user, Photo $photo, PhotoManipulatorInterface $photoManipulator): Response
    {
        if ($photo->getUser() !== $user && !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod(Request::METHOD_POST)) {
            return $this->censorPostAction($request, $user, $photo, $photoManipulator);
        } else {
            return $this->censorGetAction($request, $user, $photo, $photoManipulator);
        }
    }

    public function censorGetAction(Request $request, UserInterface $user, Photo $photo, PhotoManipulatorInterface $photoManipulator): Response
    {
        return $this->render(
            'AppBundle:Photo:censor.html.twig', [
                'photo' => $photo,
            ]
        );
    }

    public function censorPostAction(Request $request, UserInterface $user, Photo $photo, PhotoManipulatorInterface $photoManipulator): Response
    {
        $areaDataList = json_decode($request->getContent());

        if (0 === count($areaDataList)) {
            return new Response(null);
        }

        $displayWidth = $request->query->getInt('width');

        $newFilename = $photoManipulator
            ->open($photo)
            ->censor($areaDataList, $displayWidth)
            ->save();

        return new Response($newFilename);
    }
}
