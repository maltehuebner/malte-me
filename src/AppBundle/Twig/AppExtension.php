<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('markdown', array($this, 'markdownFilter')),
        );
    }

    public function markdownFilter(string $string): string
    {
        return 'foo';
    }

    public function getName()
    {
        return 'app_extension';
    }
}