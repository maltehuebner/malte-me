<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoType;
use PHPExif\Reader\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use \Malenki\Slug;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UploadController extends Controller
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
            $em = $this->getDoctrine()->getManager();

            /** @var Photo $photo */
            $photo = $uploadForm->getData();

            $photo
                ->setUser($user)
                ->setDisplayDateTime(new \DateTime())
            ;

            if ($user->isModerated()) {
                $photo->setEnabled(false);
            }

            $em->persist($photo); // first persist to generate id
            $em->flush();

            $photo
                ->setDateTime($this->getPhotoDateTime($photo))
                ->setSlug($this->createSlug($photo))
            ;

            $em->persist($photo); // second persist to save slug
            $em->flush();

            return $this->redirectToRoute(
                'show_photo',
                [
                    'slug' => $photo->getSlug()
                ]
            );
        }

        return $this->render(
            'AppBundle:Photo:upload.html.twig',
            [
                'uploadForm' => $uploadForm->createView()
            ]
        );
    }

    protected function getPhotoDateTime(Photo $photo): \DateTime
    {
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $this->getParameter('kernel.root_dir').'/../web/'.$helper->asset($photo, 'imageFile');

        try {
            $reader = Reader::factory(Reader::TYPE_NATIVE);

            $exif = $reader->getExifFromFile($path);

            $dateTime = $exif->getCreationDate();
        } catch (\Exception $e) {
            $dateTime = new \DateTime();
        }

        return $dateTime;
    }

    protected function createSlug(Photo $photo): string
    {
        return new Slug($photo->getTitle().' '.$photo->getId());
    }
}
