<?php

namespace AppBundle\Share\Network;

use AppBundle\Share\ShareableInterface\Shareable;

class PinterestShareNetwork extends AbstractShareNetwork
{
    protected $name = 'Pinterest';

    protected $icon = 'fa-pinterest';

    protected $backgroundColor = 'rgb(189, 33, 37)';

    protected $textColor = 'white';

    public function createUrlForShareable(Shareable $shareable): string
    {
        $pinterestShareUrl = 'https://www.pinterest.com/pin/create/link?url=%s&description=%s';

        return sprintf($pinterestShareUrl, urlencode($this->getShareUrl($shareable)), urlencode($this->getShareTitle($shareable)));
    }
}
