<?php

namespace AppBundle\Command;

use AppBundle\Widget\CriticalmassWidget\CalendarWidgetFactory;
use AppBundle\Widget\CriticalmassWidget\CriticalmassWidgetFactory;
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
        /** @var CriticalmassWidgetFactory $criticalmassWidgetFactory */
        $criticalmassWidgetFactory = $this->getContainer()->get(CriticalmassWidgetFactory::class);
        $criticalmassWidgetFactory->prepare();

        /** @var CalendarWidgetFactory $calendarWidgetFactory */
        $calendarWidgetFactory = $this->getContainer()->get(CriticalmassWidgetFactory::class);
        $calendarWidgetFactory->prepare();
    }

}
