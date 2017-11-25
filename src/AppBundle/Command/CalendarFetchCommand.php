<?php

namespace AppBundle\Command;

use AppBundle\Model\CalendarEntryModel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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
        $dateTime = $this->getDateTime($entry);

        $model = new CalendarEntryModel($dateTime, $entry->getPermalink(), $entry->getTitle(), $entry->getContent());

        return $model;
    }

    protected function getDateTime(EntryInterface $entry): \DateTime
    {
        $pattern = '/\((.*) (\d{1,2})\. ([A-Z][a-z]+) ([0-9]{4}), ([0-9]{1,2}):([0-9]{2,2}) - ([0-9]{1,2}):([0-9]{2,2})\)$/';

        preg_match($pattern, $entry->getTitle(), $matches);

        $timeString = sprintf('%d-%d-%d %d:%d', $matches[4], $this->getMonthNumber($matches[3]), $matches[2], $matches[5], $matches[6]);

        $dateTime = new \DateTime($timeString);

        return $dateTime;
    }

    protected function getMonthNumber(string $germanMonth): int
    {
        $monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

        return array_search($germanMonth, $monthNames) + 1;
    }
}
