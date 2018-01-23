<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class XingShareNetwork extends AbstractShareNetwork
{
    protected $name = 'XING';

    protected $icon = 'fa-xing';

    protected $backgroundColor = 'rgb(1, 101, 104)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $xingShareUrl = 'https://www.xing.com/social_plugins/share?&url=%s';

        return sprintf($xingShareUrl, urlencode($this->getPhotoUrl($photo)));
    }
}
