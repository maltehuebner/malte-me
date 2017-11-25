<?php

namespace AppBundle\Command;

use AppBundle\Model\CalendarEntryModel;
use AppBundle\Model\CriticalmassModel;
use Curl\Curl;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

class CalendarFetchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fahrradstadt:calendar:fetch')
            ->setDescription('Fetch calendar data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $feed = Reader::import('https://www.radverkehrsforum.de/calendar/index.php/CalendarFeed/');

        $entryList = [];

        /** @var EntryInterface $entry */
        foreach ($feed as $entry) {
            $model = $this->createCalendarEntryModel($entry);

            $entryList[] = $model;
        }
    }

    protected function createCalendarEntryModel(EntryInterface $entry): CalendarEntryModel
    {
        $model = new CalendarEntryModel(new \DateTime(), $entry->getPermalink(), $entry->getTitle(), $entry->getContent());

        return $model;
    }
}
