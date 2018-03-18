<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use AppBundle\PhotoUploader\PhotoUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UploadController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @ParamConverter("city", class="AppBundle:City")
     */
    public function uploadAction(Request $request, City $city, UserInterface $user, PhotoUploader $photoUploader): Response
    {
        $photo = new Photo();

        $uploadForm = $this->createForm(PhotoType::class, $photo);

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $photo = $uploadForm->getData();

            $photo = $photoUploader->handleUpload($photo, $user, $city);

            if ($photo) {
                return $this->redirectToRoute('show_photo', [
                    'photoSlug' => $photo->getSlug(),
                ]);
            }
        }

        return $this->render('AppBundle:Photo:upload.html.twig', [
            'uploadForm' => $uploadForm->createView(),
        ]);
    }
}
