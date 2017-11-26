<?php

namespace AppBundle\Widget\CriticalmassWidget;

use AppBundle\Widget\WidgetFactoryInterface;

class CriticalmassWidgetFactory implements WidgetFactoryInterface
{
    public function prepare(): WidgetFactoryInterface
    {
        return $this;
    }
}