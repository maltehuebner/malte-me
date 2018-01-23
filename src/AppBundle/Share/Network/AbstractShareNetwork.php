<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

abstract class AbstractShareNetwork implements ShareNetworkInterface
{
    protected $name;

    protected $faIcon;

    protected $backgroundColor;

    public function __construct()
    {

    }

    public function getIdentifier(): string
    {
        $reflection = new \ReflectionClass($this);

        $shortname = $reflection->getShortName();

        $identifier = strtolower(str_replace('ShareNetwork', '', $shortname));

        return $identifier;
    }

    public function createUrlForPhoto(Photo $photo): string
    {
        return 'https://www.facebook.com/sharer.php?u=https%3A%2F%2Fradverkehrsforum.de%2Fforum%2Fthread%2F1249-btw-2017-sondierungsergebnisse%2F&t=BTW%202017%3A%20Sondierungsergebnisse%20-%20Radverkehrsforum%20https%3A%2F%2Fradverkehrsforum.de%2Fforum%2Fthread%2F1249-btw-2017-sondierungsergebnisse%2F';
    }
}
