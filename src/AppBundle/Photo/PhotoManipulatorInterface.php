<?php declare(strict_types=1);

namespace AppBundle\Photo;

use AppBundle\Photo\PhotoInterface\PhotoInterface;

interface PhotoManipulatorInterface
{
    public function open(PhotoInterface $photo): PhotoManipulatorInterface;
    public function save(): string;

    public function rotate(int $angle): PhotoManipulatorInterface;
    public function censor(array $areaDataList, int $displayWidth): PhotoManipulatorInterface;
}
