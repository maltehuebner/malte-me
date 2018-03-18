<?php declare(strict_types=1);

namespace AppBundle\Photo\Cache;

use AppBundle\Photo\PhotoInterface\PhotoInterface;
use Symfony\Component\HttpFoundation\Request;

class PhotoCache extends AbstractPhotoCache
{
    public function recachePhoto(PhotoInterface $photo): PhotoCacheInterface
    {
        $this->clearImageCache($photo);

        $filename = $this->uploaderHelper->asset($photo, 'imageFile');

        $this->cacheManager->remove($filename);

        $this->imagineController->filterAction(new Request(), $filename, 'standard');
        $this->imagineController->filterAction(new Request(), $filename, 'preview');
        $this->imagineController->filterAction(new Request(), $filename, 'thumb');

        return $this;
    }

    public function clearImageCache(PhotoInterface $photo): PhotoCacheInterface
    {
        $path = $this->uploaderHelper->asset($photo, 'imageFile');

        $this->cacheManager->remove($path);

        return $this;
    }
}
