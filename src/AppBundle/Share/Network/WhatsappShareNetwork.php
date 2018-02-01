<?php

namespace AppBundle\Share\Network;

use AppBundle\Share\ShareableInterface\Shareable;

class WhatsappShareNetwork extends AbstractShareNetwork
{
    protected $name = 'WhatsApp';

    protected $icon = 'fa-whatsapp';

    protected $backgroundColor = 'rgb(37, 211, 102)';

    protected $textColor = 'white';

    public function createUrlForShareable(Shareable $shareable): string
    {
        $whatsappShareUrl = 'whatsapp://send?text=%s';

        $text = sprintf('%s%20%s', $this->getShareUrl($shareable), $shareable->getTitle());

        return sprintf($whatsappShareUrl, $text);
    }
}
