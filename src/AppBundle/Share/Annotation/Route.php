<?php

namespace AppBundle\Share\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Route extends AbstractAnnotation
{
    protected $route;

    public function getRoute(): ?string
    {
        return $this->route;
    }
}
