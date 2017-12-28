<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;
use Imagine\Imagick\Image;
use Imagine\Imagick\Imagine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class PhotoManagementController extends AbstractController
{
    public function rotateAction(Request $request, UserInterface $user, int $photoId): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        if ($photo->getUser() !== $user && !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $image = $this->createPhotoImage($photo);

        $image->rotate(-90);

        $this->savePhotoImage($photo, $image);

        return $this->redirectToRoute('show_photo', [
            'slug' => $photo->getSlug()
        ]);
    }

    public function censorAction(Request $request, UserInterface $user, int $photoId): Response
    {
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

        if (!$photo) {
            throw $this->createNotFoundException();
        }

        if ($photo->getUser() !== $user && !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod(Request::METHOD_POST)) {
            return $this->censorPostAction($request, $user, $photo);
        } else {
            return $this->censorGetAction($request, $user, $photo);
        }
    }

    public function censorGetAction(Request $request, UserInterface $user, Photo $photo): Response
    {
        return $this->render(
            'AppBundle:PhotoManagement:censor.html.twig',
            [
                'photo' => $photo,
            ]
        );
    }

    public function censorPostAction(Request $request, UserInterface $user, Photo $photo): Response
    {
        $areaDataList = json_decode($request->getContent());

        if (0 === count($areaDataList)) {
            return new Response(null);
        }

        $displayWidth = $request->query->get('width');

        $imagine = new Imagine();

        $image = $imagine->open($this->getPhotoFilename($photo));

        $size = $image->getSize();

        $factor = $size->getWidth() / $displayWidth;

        foreach ($areaDataList as $areaData) {
            $topLeftPoint = new Point($areaData->x * $factor, $areaData->y * $factor);
            $dimension = new Box($areaData->width * $factor, $areaData->height * $factor);

            $this->applyBlurArea($image, $topLeftPoint, $dimension);
        }

        $newFilename = $this->saveManipulatedImage($image, $photo);

        return new Response($newFilename);
    }

    protected function applyBlurArea(ImageInterface $image, PointInterface $topLeftPoint, BoxInterface $dimension): void
    {
        $blurImage = $image->copy();

        $pixelateDimension = $dimension->scale(0.01);

        $blurImage
            ->crop($topLeftPoint, $dimension)
            ->resize($pixelateDimension, ImageInterface::FILTER_CUBIC)
            ->resize($dimension, ImageInterface::FILTER_CUBIC)
        ;

        $image->paste($blurImage, $topLeftPoint);
    }

    protected function getPhotoFilename(Photo $photo): string
    {
        $path = $this->getParameter('kernel.root_dir') . '/../web';
        $filename = $this->get('vich_uploader.templating.helper.uploader_helper')->asset($photo, 'imageFile');

        return $path.$filename;
    }

    protected function saveManipulatedImage(ImageInterface $image, Photo $photo): string
    {
        if (!$photo->getBackupName()) {
            $newFilename = uniqid().'.JPG';

            $photo->setBackupName($photo->getImageName());

            $photo->setImageName($newFilename);

            $this->getDoctrine()->getManager()->flush();
        }

        $filename = $this->getPhotoFilename($photo);
        $image->save($filename);

        $this->recachePhoto($photo);

        return $filename;
    }

    protected function recachePhoto(Photo $photo): void
    {
        $filename = $this->get('vich_uploader.templating.helper.uploader_helper')->asset($photo, 'imageFile');

        $imagineCache = $this->get('liip_imagine.cache.manager');
        $imagineCache->remove($filename);

        $imagineController = $this->get('liip_imagine.controller');
        $imagineController->filterAction(new Request(), $filename, 'standard');
        $imagineController->filterAction(new Request(), $filename, 'preview');
        $imagineController->filterAction(new Request(), $filename, 'thumb');
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

    public function createPhotoImage(Photo $photo): Image
    {
        $imagine = new Imagine();

        $image = $imagine->open($this->getImageFilename($photo));

        return $image;
    }

    public function savePhotoImage(Photo $photo, Image $image): Image
    {
        $filename = $this->getImageFilename($photo);

        $image->save($filename);

        $this->clearImageCache($photo);

        return $image;
    }
}
