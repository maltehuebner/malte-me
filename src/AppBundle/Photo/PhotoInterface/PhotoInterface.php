<?php declare(strict_types=1);

namespace AppBundle\Photo\PhotoInterface;

interface PhotoInterface
{
    public function getImageName(): ?string;
    public function setImageName(string $imageName): PhotoInterface;

    public function getBackupName(): ?string;
    public function setBackupName(string $backupName): PhotoInterface;
}
