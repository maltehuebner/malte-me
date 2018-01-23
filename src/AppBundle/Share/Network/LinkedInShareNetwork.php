<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class LinkedInShareNetwork extends AbstractShareNetwork
{
    protected $name = 'LinkedIn';

    protected $icon = 'fa-linkedin';

    protected $backgroundColor = 'rgb(0, 122, 182)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $linkedinShareUrl = 'https://www.linkedin.com/cws/share?&url=%s';

        return sprintf($linkedinShareUrl, urlencode($this->getPhotoUrl($photo)));
    }
}
