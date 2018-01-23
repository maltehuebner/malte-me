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
        $twitterShareUrl = 'https://twitter.com/intent/tweet?text=%s&url=%s';

        return sprintf($twitterShareUrl, urlencode($photo->getTitle()), urlencode($this->getPhotoUrl($photo)));
    }
}
