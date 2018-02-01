<?php

namespace AppBundle\Share\Network;

use AppBundle\Share\ShareableInterface\Shareable;

class MailShareNetwork extends AbstractShareNetwork
{
    protected $name = 'E-Mail';

    protected $icon = 'fa-envelope-o';

    protected $backgroundColor = 'white';

    protected $textColor = 'black';

    public function createUrlForShareable(Shareable $shareable): string
    {
        $mailShareUrl = 'mailto:?subject=%s&body=%s';

        $body = sprintf('%s: %s', $this->getShareIntro($shareable), $this->getShareUrl($shareable));

        return sprintf($mailShareUrl, urlencode($this->getShareTitle($shareable)), $body);
    }
}
