<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;
use Symfony\Component\Routing\RouterInterface;

class FacebookShareNetwork extends AbstractShareNetwork
{
    public function createUrlForPhoto(Photo $photo): string
    {
        $photoUrl = str_replace('http://', 'https://', $this->router->generate('show_photo', ['slug' => $photo->getSlug()], RouterInterface::ABSOLUTE_URL));
        $photoTitle = $photo->getTitle();

        $facebookShareUrl = 'https://www.facebook.com/sharer.php?u=%s&t=%s';

        return sprintf($facebookShareUrl, urlencode($photoUrl), urlencode($photoTitle));
    }
}
