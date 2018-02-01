<?php

namespace AppBundle\Share\Network;

use AppBundle\Share\Metadata\Metadata;
use AppBundle\Share\ShareableInterface\Shareable;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractShareNetwork implements ShareNetworkInterface
{
    protected $name;

    protected $icon;

    protected $backgroundColor;

    protected $textColor;

    protected $metadata;

    public function __construct(Metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    public function getIdentifier(): string
    {
        $reflection = new \ReflectionClass($this);

        $shortname = $reflection->getShortName();

        $identifier = strtolower(str_replace('ShareNetwork', '', $shortname));

        return $identifier;
    }

    protected function getShareUrl(Shareable $shareable): string
    {
        $shareableUrl = $this->metadata->getShareUrl($shareable);

        return str_replace('http://', 'https://', $shareableUrl);
    }

    public function createShareUrl(Shareable $shareable): string
    {
        return '';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }
}
