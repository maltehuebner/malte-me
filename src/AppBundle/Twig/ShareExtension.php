<?php

namespace AppBundle\Twig;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
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
            new \Twig_Function('shareUrl', [$this, 'shareUrl']),
            new \Twig_Function('shareLink', [$this, 'shareLink']),
            new \Twig_Function('shareButton', [$this, 'shareButton']),
        ];
    }

    public function shareUrl(Photo $photo, string $network): string
    {
        return $this->sharer->createUrlForPhoto($photo, $network);
    }

    public function shareLink(Photo $photo, string $network, string $caption, array $class = []): string
    {
        $link = '<a href="%" class="%s">%s</a>';

        $class = array_merge($class, ['share']);

        return sprintf($link, $this->shareUrl($photo, $network), implode(' ', $class), $caption);
    }

    public function shareButton(Photo $photo, string $network, string $caption, array $class = []): string
    {
        return $this->shareLink($photo, $network, $caption, $class);
    }

    public function getName(): string
    {
        return 'share_extension';
    }
}
