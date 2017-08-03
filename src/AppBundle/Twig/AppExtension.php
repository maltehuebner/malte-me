<?php

namespace AppBundle\Twig;

use AppBundle\Markdown\FahrradstadtMarkdown;
use AppBundle\Seo\SeoPage;
use cebe\markdown\Markdown;

class AppExtension extends \Twig_Extension
{
    protected $markdown;

    protected $seoPage;

    public function __construct(Markdown $markdown, SeoPage $seoPage)
    {
        $this->markdown = $markdown;
        $this->seoPage = $seoPage;
    }

    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('markdown', [$this, 'markdownFilter'], ['is_safe' => ['html']]),
        ];
    }

    public function markdownFilter(string $string): string
    {
        return $this->markdown->parse($string);
    }

    public function getSeoPage(): SeoPage
    {
        return $this->seoPage;
    }

    public function getName(): string
    {
        return 'app_extension';
    }
}
