<?php

namespace AppBundle\Share\Annotation;

/**
 * @Annotation
 */
class Intro extends AbstractAnnotation
{
    protected $intro;

    public function getIntro(): ?string
    {
        return $this->intro;
    }
}
