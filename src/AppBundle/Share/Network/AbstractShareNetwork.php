<?php

namespace AppBundle\Share\Network;

use AppBundle\Share\Annotation\Route;
use AppBundle\Share\Annotation\RouteParameter;
use AppBundle\Share\ShareableInterface\Shareable;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractShareNetwork implements ShareNetworkInterface
{
    protected $name;

    protected $icon;

    protected $backgroundColor;

    protected $textColor;

    protected $router;

    protected $annotationReader;

    public function __construct(Router $router, AnnotationReader $annotationReader)
    {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
    }

    public function getIdentifier(): string
    {
        $reflection = new \ReflectionClass($this);

        $shortname = $reflection->getShortName();

        $identifier = strtolower(str_replace('ShareNetwork', '', $shortname));

        return $identifier;
    }

    protected function getShareUrl(Shareable $shareable): string
    {
        $shareableUrl = $this->router->generate($this->getRouteName($shareable), $this->getRouteParameter($shareable), RouterInterface::ABSOLUTE_URL);

        return str_replace('http://', 'https://', $shareableUrl);
    }

    public function createShareUrl(Shareable $shareable): string
    {
        return '';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    protected function getRouteName(Shareable $shareable): string
    {
        $reflectionClass = new \ReflectionClass($shareable);
        $routeAnnotation = $this->annotationReader->getClassAnnotation($reflectionClass, Route::class);

        return $routeAnnotation->getRoute();
    }

    protected function getRouteParameter(Shareable $shareable): array
    {
        $parameter = [];

        $reflectionClass = new \ReflectionClass($shareable);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $key => $property) {
            $parameterAnnotation = $this->annotationReader->getPropertyAnnotation($property, RouteParameter::class);

            if ($parameterAnnotation) {
                $getMethodName = sprintf('get%s', ucfirst($property->getName()));

                if (!$reflectionClass->hasMethod($getMethodName)) {
                    continue;
                }

                $value = $shareable->$getMethodName();

                $parameter[$parameterAnnotation->getName()] = $value;
            }
        }

        return $parameter;
    }
}
