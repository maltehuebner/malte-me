<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator\Storage;

use AppBundle\PhotoManipulator\Cache\PhotoCacheInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

abstract class AbstractPhotoStorage implements PhotoStorageInterface
{
    /** @var UploaderHelper $uploaderHelper */
    protected $uploaderHelper;

    /** @var PhotoCacheInterface $photoCache */
    protected $photoCache;

    /** @var string $webDirectory */
    protected $webDirectory;

    public function __construct(UploaderHelper $uploaderHelper, PhotoCacheInterface $photoCache, string $webDirectory)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->webDirectory = $webDirectory;
        $this->photoCache = $photoCache;
    }
}
