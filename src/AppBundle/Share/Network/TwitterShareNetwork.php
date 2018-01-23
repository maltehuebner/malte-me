<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class TwitterShareNetwork extends AbstractShareNetwork
{
    protected $name = 'twitter';

    protected $icon = 'fa-twitter';

    protected $backgroundColor = 'rgb(85, 172, 238)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $twitterShareUrl = 'https://twitter.com/share?url=%s&text=%s';

        return sprintf($twitterShareUrl, urlencode($this->getPhotoUrl($photo)), urlencode($photo->getTitle()));
    }
}
