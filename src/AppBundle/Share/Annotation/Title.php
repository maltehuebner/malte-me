<?php declare(strict_types=1);

namespace AppBundle\Share\Annotation;

/**
 * @Annotation
 */
class Title extends AbstractAnnotation
{
    protected $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
