<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use AppBundle\PhotoUploader\PhotoUploader;
use PHPExif\Reader\Reader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use \Malenki\Slug;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UploadController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function uploadAction(Request $request, UserInterface $user): Response
    {
        $photo = new Photo();

        $uploadForm = $this->createForm(PhotoType::class, $photo);

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $photo = $uploadForm->getData();

            /** @var PhotoUploader $photoUploader */
            $photoUploader = $this->get('app.photo_uploader');

            $photo = $photoUploader->handleUpload($photo, $user, $this->getCity($request));

            if ($photo) {
                return $this->redirectToRoute(
                    'show_photo',
                    [
                        'slug' => $photo->getSlug()
                    ]
                );
            }
        }

        return $this->render(
            'AppBundle:Photo:upload.html.twig',
            [
                'uploadForm' => $uploadForm->createView()
            ]
        );
    }
}
