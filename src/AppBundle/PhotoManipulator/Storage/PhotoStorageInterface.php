<?php

namespace AppBundle\PhotoManipulator\Storage;

use AppBundle\PhotoManipulator\PhotoInterface\PhotoInterface;
use Imagine\Image\ImageInterface;

interface PhotoStorageInterface
{
    public function open(PhotoInterface $photo): ImageInterface;
    public function save(PhotoInterface $photo, ImageInterface $image): string;
}
