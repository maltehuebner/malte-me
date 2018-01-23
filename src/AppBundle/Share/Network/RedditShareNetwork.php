<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class RedditShareNetwork extends AbstractShareNetwork
{
    protected $name = 'Reddit';

    protected $icon = 'fa-reddit';

    protected $backgroundColor = 'rgb(255, 69, 1)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $redditShareUrl = 'https://ssl.reddit.com/submit?url=%s';

        return sprintf($redditShareUrl, urlencode($this->getPhotoUrl($photo)));
    }
}
