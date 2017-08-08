<?php

namespace AppBundle\Twig;

use AppBundle\Entity\City;
use AppBundle\Markdown\FahrradstadtMarkdown;
use AppBundle\Seo\SeoPage;
use cebe\markdown\Markdown;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;

class AppExtension extends \Twig_Extension
{
    /** @var City $currentCity */
    protected $currentCity = null;

    /** @var Registry $doctrine */
    protected $doctrine;

    /** @var Session $session */
    protected $session;

    /** @var Markdown $markdown */
    protected $markdown;

    /** @var SeoPage $seoPage */
    protected $seoPage;

    public function __construct(Registry $doctrine, Session $session, Markdown $markdown, SeoPage $seoPage)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->markdown = $markdown;
        $this->seoPage = $seoPage;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('getCity', [$this, 'getCity']),
            new \Twig_Function('seoPage', [$this, 'seoPageFunction'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('markdown', [$this, 'markdownFilter'], ['is_safe' => ['html']]),
        ];
    }

    public function markdownFilter(string $string = null): string
    {
        return $this->markdown->parse($string);
    }

    public function seoPageFunction(): SeoPage
    {
        return $this->seoPage;
    }

    public function getCity(): ?City
    {
        if (!$this->session->has('cityId')) {
            throw new \InvalidArgumentException('City has to be set before calling getCity()');
        }

        if (!$this->currentCity) {
            /** @var int $cityId */
            $cityId = $this->session->get('cityId');

            $this->currentCity = $this->doctrine->getRepository(City::class)->find($cityId);
        }

        return $this->currentCity;
    }

    public function getName(): string
    {
        return 'app_extension';
    }
}
