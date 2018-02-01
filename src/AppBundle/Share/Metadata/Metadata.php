<?php

namespace AppBundle\Share\Metadata;

use AppBundle\Share\Annotation\Intro;
use AppBundle\Share\Annotation\Route;
use AppBundle\Share\Annotation\RouteParameter;
use AppBundle\Share\Annotation\Title;
use AppBundle\Share\ShareableInterface\Shareable;
use Caldera\YourlsApiManager\YourlsApiManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class Metadata
{
    /** @var Router $router */
    protected $router;

    /** @var AnnotationReader $annotationReader */
    protected $annotationReader;

    /** @var YourlsApiManager $yourlsApiManager */
    protected $yourlsApiManager;

    public function __construct(Router $router, AnnotationReader $annotationReader, YourlsApiManager $yourlsApiManager)
    {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->yourlsApiManager = $yourlsApiManager;
    }

    public function getShareUrl(Shareable $shareable): string
    {
        return $this->router->generate(
            $this->getRouteName($shareable),
            $this->getRouteParameter($shareable),
            RouterInterface::ABSOLUTE_URL
        );
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

    public function getShareTitle(Shareable $shareable): ?string
    {
        $reflectionClass = new \ReflectionClass($shareable);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $key => $property) {
            $titleAnnotation = $this->annotationReader->getPropertyAnnotation($property, Title::class);

            if ($titleAnnotation) {
                $getMethodName = sprintf('get%s', ucfirst($property->getName()));

                if (!$reflectionClass->hasMethod($getMethodName)) {
                    continue;
                }

                return $shareable->$getMethodName();
            }
        }

        return null;
    }

    public function getShareIntro(Shareable $shareable): ?string
    {
        $reflectionClass = new \ReflectionClass($shareable);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $key => $property) {
            $introAnnotation = $this->annotationReader->getPropertyAnnotation($property, Intro::class);

            if ($introAnnotation) {
                $getMethodName = sprintf('get%s', ucfirst($property->getName()));

                if (!$reflectionClass->hasMethod($getMethodName)) {
                    continue;
                }

                return $shareable->$getMethodName();
            }
        }

        return null;
    }
}
