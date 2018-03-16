<?php

namespace AppBundle\Widget;

interface WidgetFactoryInterface
{
    public function prepare(): WidgetFactoryInterface;
}