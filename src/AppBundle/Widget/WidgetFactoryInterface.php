<?php declare(strict_types=1);

namespace AppBundle\Widget;

interface WidgetFactoryInterface
{
    public function prepare(): WidgetFactoryInterface;
}
