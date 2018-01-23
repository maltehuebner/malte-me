<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

interface ShareNetworkInterface
{
    public function getIdentifier(): string;
    public function createUrlForPhoto(Photo $photo): string;
}
