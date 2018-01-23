<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;

class WhatsappShareNetwork extends AbstractShareNetwork
{
    protected $name = 'WhatsApp';

    protected $icon = 'fa-whatsapp';

    protected $backgroundColor = 'rgb(37, 211, 102)';

    protected $textColor = 'white';

    public function createUrlForPhoto(Photo $photo): string
    {
        $whatsappShareUrl = 'whatsapp://send?text=%s';

        $text = sprintf('%s%20%s', $this->getPhotoUrl($photo), $photo->getTitle());

        return sprintf($whatsappShareUrl, $text);
    }
}
