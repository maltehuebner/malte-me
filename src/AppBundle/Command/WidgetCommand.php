<?php

namespace AppBundle\Command;

use AppBundle\Model\CalendarEntryModel;
use AppBundle\Widget\CriticalmassWidget\CriticalmassWidgetFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

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
    }

}
