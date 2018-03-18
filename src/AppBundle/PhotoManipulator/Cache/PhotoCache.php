<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator\Cache;

use AppBundle\PhotoManipulator\PhotoInterface\PhotoInterface;
use Liip\ImagineBundle\Controller\ImagineController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class PhotoCache
{
    /** @var UploaderHelper $uploaderHelper */
    protected $uploaderHelper;

    /** @var CacheManager $cacheManager */
    protected $cacheManager;

    /** @var ImagineController $imagineController */
    protected $imagineController;

    public function __construct(UploaderHelper $uploaderHelper, CacheManager $cacheManager, ImagineController $imagineController, string $webDirectory)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->cacheManager = $cacheManager;
        $this->imagineController = $imagineController;
    }

    public function recachePhoto(PhotoInterface $photo): PhotoCache
    {
        $this->clearImageCache($photo);

        $filename = $this->uploaderHelper->asset($photo, 'imageFile');

        $this->cacheManager->remove($filename);

        $this->imagineController->filterAction(new Request(), $filename, 'standard');
        $this->imagineController->filterAction(new Request(), $filename, 'preview');
        $this->imagineController->filterAction(new Request(), $filename, 'thumb');

        return $this;
    }

    public function clearImageCache(PhotoInterface $photo): PhotoCache
    {
        $path = $this->uploaderHelper->asset($photo, 'imageFile');

        $this->cacheManager->remove($path);

        return $this;
    }
}
