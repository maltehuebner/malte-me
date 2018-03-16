<?php

namespace AppBundle\Widget;

use Psr\Log\LoggerInterface;

class WidgetPreparer
{
    /** @var array $widgetList */
    protected $widgetList = [];

    /** @var LoggerInterface $logger */
    protected $logger;

    public function addWidgetFactory(WidgetFactoryInterface $widgetFactory, LoggerInterface $logger): WidgetPreparer
    {
        $this->widgetList[] = $widgetFactory;

        $this->logger = $logger;

        return $this;
    }

    public function prepare(): WidgetPreparer
    {
        /** @var WidgetFactoryInterface $widgetFactory */
        foreach ($this->widgetList as $widgetFactory) {
            try {
                $widgetFactory->prepare();
            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
            }
        }

        return $this;
    }
}
