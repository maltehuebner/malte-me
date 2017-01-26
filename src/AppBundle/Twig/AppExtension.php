<?php

namespace AppBundle\Twig;

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
        $parser = new \cebe\markdown\Markdown();
        return $parser->parse($string);
    }

    public function getName()
    {
        return 'app_extension';
    }
}