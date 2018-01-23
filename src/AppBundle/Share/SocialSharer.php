<?php

namespace AppBundle\Share;

use AppBundle\Entity\Photo;
use AppBundle\Share\Network\ShareNetworkInterface;

class SocialSharer
{
    protected $shareNetworkList = [];

    public function addShareNetwork(ShareNetworkInterface $shareNetwork): SocialSharer
    {
        $this->shareNetworkList[$shareNetwork->getIdentifier()] = $shareNetwork;

        return $this;
    }

    public function createUrlForPhoto(Photo $photo, string $network): string
    {
        return $this->shareNetworkList[$network]->createUrlForPhoto($photo);
    }

    public function getNetwork(string $identifier): ShareNetworkInterface
    {
        if (array_key_exists($identifier, $this->shareNetworkList)) {
            return $this->shareNetworkList[$identifier];
        }

        throw new \Exception();
    }
}
