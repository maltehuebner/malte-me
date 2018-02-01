<?php

namespace AppBundle\Share\Network;

use AppBundle\Share\ShareableInterface\Shareable;

interface ShareNetworkInterface
{
    public function getIdentifier(): string;
    public function createUrlForShareable(Shareable $shareable): string;
    public function getName(): string;
    public function getIcon(): string;
    public function getBackgroundColor(): string;
    public function getTextColor(): string;
}
