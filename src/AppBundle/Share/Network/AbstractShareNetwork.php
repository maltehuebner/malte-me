<?php

namespace AppBundle\Share\Network;

use AppBundle\Entity\Photo;
use AppBundle\Share\Annotation\Route;
use AppBundle\Share\Annotation\RouteParameter;
use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\DriverChain;
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

    protected function getPhotoUrl(Photo $photo): string
    {
        $reflectionClass = new \ReflectionClass($photo);
        $routeAnnotation = $this->annotationReader->getClassAnnotation($reflectionClass, Route::class);

        $photoUrl = $this->router->generate($routeAnnotation->getRoute(), $this->getRouteParameter($photo), RouterInterface::ABSOLUTE_URL);

        return str_replace('http://', 'https://', $photoUrl);
    }

    public function createUrlForPhoto(Photo $photo): string
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

    protected function getRouteParameter(Photo $photo): array
    {
        $parameter = [];

        $reflectionClass = new \ReflectionClass($photo);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $key => $property) {
            $parameterAnnotation = $this->annotationReader->getPropertyAnnotation($property, RouteParameter::class);

            if ($parameterAnnotation) {
                $getMethodName = sprintf('get%s', ucfirst($property->getName()));

                if (!$reflectionClass->hasMethod($getMethodName)) {
                    continue;
                }

                $value = $photo->$getMethodName();

                $parameter[$parameterAnnotation->getName()] = $value;
            }
        }

        return $parameter;
    }
}
