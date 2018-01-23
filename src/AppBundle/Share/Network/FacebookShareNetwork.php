<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class FacebookShareNetwork extends AbstractShareNetwork
{
    public function createUrlForPhoto(Photo $photo): string
    {
        $facebookShareUrl = 'https://www.facebook.com/sharer.php?u=%s&t=%s';

        return sprintf($facebookShareUrl, urlencode($this->getPhotoUrl($photo)), urlencode($photo->getTitle()));
    }
}
