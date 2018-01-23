<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class GoogleShareNetwork extends AbstractShareNetwork
{
    protected $name = 'Google+';

    protected $icon = 'fa-google-plus';

    protected $backgroundColor = 'rgb(220, 78, 65)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $googleShareUrl = 'https://plus.google.com/share?url=%s';

        return sprintf($googleShareUrl, urlencode($this->getPhotoUrl($photo)));
    }
}

