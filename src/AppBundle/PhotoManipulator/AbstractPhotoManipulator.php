<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator;

use AppBundle\Entity\Photo;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Imagick\Image;
use Imagine\Imagick\Imagine;
use Liip\ImagineBundle\Controller\ImagineController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

abstract class AbstractPhotoManipulator
{
    /** @var Photo $photo */
    protected $photo;

    /** @var ImageInterface $image */
    protected $image;

    /** @var ImagineInterface $imagine */
    protected $imagine;

    /** @var RegistryInterface $registry */
    protected $registry;

    /** @var UploaderHelper $uploaderHelper */
    protected $uploaderHelper;

    /** @var CacheManager $cacheManager */
    protected $cacheManager;

    /** @var string $uploadDirectory */
    protected $uploadDirectory;

    /** @var ImagineController $imagineController */
    protected $imagineController;

    public function __construct(RegistryInterface $registry, UploaderHelper $uploaderHelper, CacheManager $cacheManager, ImagineController $imagineController, string $uploadDirectory)
    {
        $this->registry = $registry;
        $this->uploaderHelper = $uploaderHelper;
        $this->uploadDirectory = $uploadDirectory;
        $this->cacheManager = $cacheManager;
        $this->imagineController = $imagineController;
    }

    public function setPhoto(Photo $photo): AbstractPhotoManipulator
    {
        $this->photo = $photo;

        $this->createPhotoImage();

        return $this;
    }

    public function getPhoto(): Photo
    {
        return $this->photo;
    }

    protected function getImageFilename(): string
    {
        $path = $this->uploaderHelper->asset($this->photo, 'imageFile');

        $filename = sprintf('%s%s', $this->uploadDirectory, $path);

        return $filename;
    }

    protected function saveManipulatedImage(ImageInterface $image, Photo $photo): string
    {
        if (!$photo->getBackupName()) {
            $newFilename = uniqid().'.JPG';

            $photo->setBackupName($photo->getImageName());

            $photo->setImageName($newFilename);

            $this->registry->getManager()->flush();
        }

        $filename = $this->getImageFilename();
        $image->save($filename);

        $this->recachePhoto($photo);

        return $filename;
    }

    public function createPhotoImage(): AbstractPhotoManipulator
    {
        $imagine = new Imagine();

        $this->image = $imagine->open($this->getImageFilename());

        return $this;
    }

    protected function recachePhoto(Photo $photo): AbstractPhotoManipulator
    {
        $filename = $this->uploaderHelper->asset($photo, 'imageFile');

        $this->cacheManager->remove($filename);

        $this->imagineController->filterAction(new Request(), $filename, 'standard');
        $this->imagineController->filterAction(new Request(), $filename, 'preview');
        $this->imagineController->filterAction(new Request(), $filename, 'thumb');

        return $this;
    }

    protected function clearImageCache(Photo $photo): AbstractPhotoManipulator
    {
        $path = $this->uploaderHelper->asset($this->photo, 'imageFile');

        $this->cacheManager->remove($path);

        return $this;
    }
}
