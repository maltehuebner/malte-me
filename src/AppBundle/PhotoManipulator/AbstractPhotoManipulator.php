<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator;

use AppBundle\Entity\Photo;
use AppBundle\PhotoManipulator\PhotoInterface\PhotoInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Imagick\Image;
use Imagine\Imagick\Imagine;
use Liip\ImagineBundle\Controller\ImagineController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

abstract class AbstractPhotoManipulator implements PhotoManipulatorInterface
{
    /** @var PhotoInterface $photo */
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

    /** @var string $webDirectory */
    protected $webDirectory;

    /** @var ImagineController $imagineController */
    protected $imagineController;

    public function __construct(RegistryInterface $registry, UploaderHelper $uploaderHelper, CacheManager $cacheManager, ImagineController $imagineController, string $webDirectory)
    {
        $this->registry = $registry;
        $this->uploaderHelper = $uploaderHelper;
        $this->webDirectory = $webDirectory;
        $this->cacheManager = $cacheManager;
        $this->imagineController = $imagineController;
    }

    public function open(PhotoInterface $photo): PhotoManipulatorInterface
    {
        $this->photo = $photo;

        $this->createPhotoImage();

        return $this;
    }

    public function getPhoto(): PhotoInterface
    {
        return $this->photo;
    }

    protected function getImageFilename(): string
    {
        $path = $this->uploaderHelper->asset($this->photo, 'imageFile');

        $filename = sprintf('%s%s', $this->webDirectory, $path);

        return $filename;
    }

    public function save(): string
    {
        if (!$this->photo->getBackupName()) {
            $newFilename = uniqid().'.JPG';

            $this->photo->setBackupName($this->photo->getImageName());

            $this->photo->setImageName($newFilename);

            $this->registry->getManager()->flush();
        }

        $filename = $this->getImageFilename();
        $this->image->save($filename);

        $this->recachePhoto();

        return $filename;
    }

    protected function createPhotoImage(): AbstractPhotoManipulator
    {
        $imagine = new Imagine();

        $this->image = $imagine->open($this->getImageFilename());

        return $this;
    }

    protected function recachePhoto(): AbstractPhotoManipulator
    {
        $this->clearImageCache();

        $filename = $this->uploaderHelper->asset($this->photo, 'imageFile');

        $this->imagineController->filterAction(new Request(), $filename, 'standard');
        $this->imagineController->filterAction(new Request(), $filename, 'preview');
        $this->imagineController->filterAction(new Request(), $filename, 'thumb');

        return $this;
    }

    protected function clearImageCache(): AbstractPhotoManipulator
    {
        $path = $this->uploaderHelper->asset($this->photo, 'imageFile');

        $this->cacheManager->remove($path);

        return $this;
    }
}
