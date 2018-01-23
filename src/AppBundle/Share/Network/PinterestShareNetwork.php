<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class PinterestShareNetwork extends AbstractShareNetwork
{
    protected $name = 'Pinterest';

    protected $icon = 'fa-pinterest';

    protected $backgroundColor = 'rgb(189, 33, 37)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $pinterestShareUrl = 'https://www.pinterest.com/pin/create/link?url=%s&description=%s';

        return sprintf($pinterestShareUrl, urlencode($this->getPhotoUrl($photo)), urlencode($photo->getTitle()));
    }
}
