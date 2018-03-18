<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator\Cache;

use AppBundle\PhotoManipulator\PhotoInterface\PhotoInterface;

interface PhotoCacheInterface
{
    public function recachePhoto(PhotoInterface $photo): PhotoCacheInterface;
    public function clearImageCache(PhotoInterface $photo): PhotoCacheInterface;
}
