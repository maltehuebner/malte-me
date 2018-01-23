<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

interface ShareNetworkInterface
{
    public function getIdentifier(): string;
    public function createUrlForPhoto(Photo $photo): string;
    public function getName(): string;
    public function getIcon(): string;
    public function getBackgroundColor(): string;
    public function getTextColor(): string;
}
