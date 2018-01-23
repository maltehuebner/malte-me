<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class TwitterShareNetwork extends AbstractShareNetwork
{
    public function createUrlForPhoto(Photo $photo): string
    {
        $twitterShareUrl = 'https://twitter.com/intent/tweet?text=%s&url=%s';

        return sprintf($twitterShareUrl, urlencode($photo->getTitle()), urlencode($this->getPhotoUrl($photo)));
    }
}
