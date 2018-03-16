<?php declare(strict_types=1);

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
