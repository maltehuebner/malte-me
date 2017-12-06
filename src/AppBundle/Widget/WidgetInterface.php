<?php

namespace AppBundle\Widget;

interface WidgetInterface
{
    public function render(): WidgetDataInterface;
}
