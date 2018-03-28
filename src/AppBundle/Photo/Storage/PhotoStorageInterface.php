<?php

namespace AppBundle\Photo\Storage;

use AppBundle\Photo\PhotoInterface\PhotoInterface;
use Imagine\Image\ImageInterface;

interface PhotoStorageInterface
{
    public function open(PhotoInterface $photo): ImageInterface;
    public function save(PhotoInterface $photo, ImageInterface $image): string;
}
