<?php

namespace AppBundle\Twig;

use AppBundle\Entity\City;
use AppBundle\Markdown\FahrradstadtMarkdown;
use AppBundle\Seo\SeoPage;
use AppBundle\Share\SocialSharer;
use cebe\markdown\Markdown;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;

class ShareExtension extends \Twig_Extension
{
    /** @var SocialSharer $sharer */
    protected $sharer;
    public function __construct(SocialSharer $sharer)
    {
        $this->sharer = $sharer;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('share', [$this, 'share']),
        ];
    }

    public function share()
    {
        echo "FOO";
    }

    public function getName(): string
    {
        return 'share_extension';
    }
}
