<?php

namespace AppBundle\Share\Annotation;

/**
 * @Annotation
 */
class Shareable
{
    protected $route;

    public function __construct($options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }
}
