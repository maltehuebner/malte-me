<?php

namespace AppBundle\Widget;

class WidgetPreparer
{
    protected $widgetList = [];

    public function addWidgetFactory(WidgetFactoryInterface $widgetFactory): WidgetPreparer
    {
        $this->widgetList[] = $widgetFactory;

        return $this;
    }

    public function prepare(): WidgetPreparer
    {
        /** @var WidgetFactoryInterface $widgetFactory */
        foreach ($this->widgetList as $widgetFactory) {
            $widgetFactory->prepare();
        }

        return $this;
    }
}