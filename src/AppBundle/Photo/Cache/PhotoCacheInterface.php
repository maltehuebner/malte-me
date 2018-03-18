<?php declare(strict_types=1);

namespace AppBundle\Photo\Cache;

use AppBundle\Photo\PhotoInterface\PhotoInterface;

interface PhotoCacheInterface
{
    public function recachePhoto(PhotoInterface $photo): PhotoCacheInterface;
    public function clearImageCache(PhotoInterface $photo): PhotoCacheInterface;
}
