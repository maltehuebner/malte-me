<?php

namespace AppBundle\Command;

use AppBundle\Widget\CalendarWidget\CalendarWidgetFactory;
use AppBundle\Widget\CriticalmassWidget\CriticalmassWidgetFactory;
use AppBundle\Widget\WidgetPreparer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WidgetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fahrradstadt:widget')
            ->setDescription('Refresh widgets')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var WidgetPreparer $widgetPreparer */
        $widgetPreparer = $this->getContainer()->get(WidgetPreparer::class);
        $widgetPreparer->prepare();
    }
}
