<?php declare(strict_types=1);

namespace AppBundle\PhotoManipulator\PhotoInterface;

interface PhotoInterface
{
    public function getImageName(): ?string;
}
