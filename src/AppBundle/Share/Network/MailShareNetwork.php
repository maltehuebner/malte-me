<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class MailShareNetwork extends AbstractShareNetwork
{
    protected $name = 'E-Mail';

    protected $icon = 'fa-envelope-o';

    protected $backgroundColor = 'white';

    protected $textColor = 'black';

    public function createUrlForPhoto(Photo $photo): string
    {
        $mailShareUrl = 'mailto:?subject=%s&body=%s';

        $body = sprintf('%s: %s', $photo->getDescription(), $this->getPhotoUrl($photo));

        return sprintf($mailShareUrl, urlencode($photo->getTitle()), $body);
    }
}
