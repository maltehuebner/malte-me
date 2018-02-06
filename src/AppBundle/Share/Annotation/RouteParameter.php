<?php

namespace AppBundle\Share\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class RouteParameter extends AbstractAnnotation
{
    protected $name;

    public function getName(): ?string
    {
        return $this->name;
    }
}
