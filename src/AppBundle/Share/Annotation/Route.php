<?php

namespace AppBundle\Share\Annotation;

/**
 * @Annotation
 */
class Route extends AbstractAnnotation
{
    protected $route;

    public function getRoute(): ?string
    {
        return $this->route;
    }
}
