<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractShareNetwork implements ShareNetworkInterface
{
    protected $name;

    protected $faIcon;

    protected $backgroundColor;

    protected $textColor;

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getIdentifier(): string
    {
        $reflection = new \ReflectionClass($this);

        $shortname = $reflection->getShortName();

        $identifier = strtolower(str_replace('ShareNetwork', '', $shortname));

        return $identifier;
    }

    protected function getPhotoUrl(Photo $photo): string
    {
        $photoUrl = $this->router->generate('show_photo', ['slug' => $photo->getSlug()], RouterInterface::ABSOLUTE_URL);

        return str_replace('http://', 'https://', $photoUrl);
    }

    public function createUrlForPhoto(Photo $photo): string
    {
        return '';
    }
}
