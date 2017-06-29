<?php

namespace AppBundle\Twig;

use AppBundle\Markdown\FahrradstadtMarkdown;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('markdown', array($this, 'markdownFilter'), array('is_safe' => array('html'))),
        );
    }

    public function markdownFilter(string $string): string
    {
        $parser = new FahrradstadtMarkdown();
        return $parser->parse($string);
    }

    public function getName()
    {
        return 'app_extension';
    }
}
