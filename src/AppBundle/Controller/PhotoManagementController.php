<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use Gregwar\Image\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class PhotoManagementController extends Controller
{
    public function rotateAction(Request $request, UserInterface $user, int $photoId): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        if ($photo->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $filename = $this->getImageFilename($photo);

        var_dump($filename);

        $image = Image::open($filename);
        $image->rotate(90);

        $success = $image->save($filename, 'jpeg', 100);

        $this->clearImageCache($photo);

        return $this->redirectToRoute('show_photo', [
            'slug' => $photo->getSlug()
        ]);
    }

    protected function clearImageCache(Photo $photo): void
    {
        $cacheManager = $this->get('liip_imagine.cache.manager');

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($photo, 'imageFile');

        $cacheManager->remove($path);
    }

    protected function getImageFilename(Photo $photo): string
    {
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($photo, 'imageFile');

        $webDirectory = $this->getParameter('web_dir');

        $filename = $webDirectory.$path;

        return $filename;
    }
}
