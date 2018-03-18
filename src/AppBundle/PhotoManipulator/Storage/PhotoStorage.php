<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator\Storage;

use AppBundle\PhotoManipulator\Cache\PhotoCache;
use AppBundle\PhotoManipulator\PhotoInterface\PhotoInterface;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class PhotoStorage
{
    /** @var UploaderHelper $uploaderHelper */
    protected $uploaderHelper;

    /** @var PhotoCache $photoCache */
    protected $photoCache;

    /** @var string $webDirectory */
    protected $webDirectory;

    public function __construct(UploaderHelper $uploaderHelper, PhotoCache $photoCache, string $webDirectory)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->webDirectory = $webDirectory;
        $this->photoCache = $photoCache;
    }

    public function open(PhotoInterface $photo): ImageInterface
    {
        $imagine = new Imagine();

        $image = $imagine->open($this->getImageFilename($photo));

        return $image;
    }

    public function save(PhotoInterface $photo, ImageInterface $image): string
    {
        if (!$photo->getBackupName()) {
            $newFilename = uniqid().'.JPG';

            $photo->setBackupName($photo->getImageName());

            $photo->setImageName($newFilename);
        }

        $this->photoCache->recachePhoto($photo);

        $filename = $this->getImageFilename($photo);
        $image->save($filename);

        return $filename;
    }

    protected function getImageFilename(PhotoInterface $photo): string
    {
        $path = $this->uploaderHelper->asset($photo, 'imageFile');

        $filename = sprintf('%s%s', $this->webDirectory, $path);

        return $filename;
    }
}
