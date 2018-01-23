<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class FacebookShareNetwork extends AbstractShareNetwork
{
    protected $name = 'facebook';

    protected $icon = 'fa-facebook';

    protected $backgroundColor = 'rgb(59, 90, 153)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $facebookShareUrl = 'https://www.facebook.com/sharer.php?u=%s&t=%s';

        return sprintf($facebookShareUrl, urlencode($this->getPhotoUrl($photo)), urlencode($photo->getTitle()));
    }
}
