<?php declare(strict_types=1);

namespace AppBundle\Widget;

interface WidgetInterface
{
    public function render(): ?WidgetDataInterface;
}
