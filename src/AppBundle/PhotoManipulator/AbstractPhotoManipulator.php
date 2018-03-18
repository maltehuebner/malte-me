<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator;

use AppBundle\Entity\Photo;
use AppBundle\PhotoManipulator\PhotoInterface\PhotoInterface;
use AppBundle\PhotoManipulator\Storage\PhotoStorage;
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

    /** @var PhotoStorage $photoStorage */
    protected $photoStorage;

    public function __construct(RegistryInterface $registry, PhotoStorage $photoStorage)
    {
        $this->registry = $registry;
        $this->photoStorage = $photoStorage;
    }

    public function open(PhotoInterface $photo): PhotoManipulatorInterface
    {
        $this->photo = $photo;

        $this->image = $this->photoStorage->open($photo);

        return $this;
    }

    public function save(): string
    {
        return $this->photoStorage->save($this->photo, $this->image);
    }

    public function getPhoto(): PhotoInterface
    {
        return $this->photo;
    }
}
