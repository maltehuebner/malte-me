<?php

namespace AppBundle\Widget\CalendarWidget;

use AppBundle\Widget\AbstractWidgetFactory;
use AppBundle\Widget\WidgetFactoryInterface;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

class CalendarWidgetFactory extends AbstractWidgetFactory
{
    public function prepare(): WidgetFactoryInterface
    {
        $feed = Reader::import('https://www.radverkehrsforum.de/calendar/index.php/CalendarFeed/');

        $calendar = new CalendarModel();

        /** @var EntryInterface $entry */
        foreach ($feed as $entry) {
            $eventModel = $this->createCalendarEntryModel($entry);

            if ($eventModel) {
                $calendar->addEvent($eventModel);
            }
        }

        $this->cacheData($calendar);

        return $this;
    }

    protected function createCalendarEntryModel(EntryInterface $entry): ?CalendarEventModel
    {
        $title = $this->getTitle($entry);
        $dateTime = $this->getDateTime($entry);

        if (!$dateTime) {
            return null;
        }

        $model = new CalendarEventModel($dateTime, $entry->getPermalink(), $title, $entry->getContent());

        return $model;
    }

    protected function getTitle(EntryInterface $entry): string
    {
        $pattern = '/^(.*) \((.*)\)$/';

        preg_match($pattern, $entry->getTitle(), $matches);

        return $matches[1];
    }

    protected function getDateTime(EntryInterface $entry): ?\DateTime
    {
        $pattern = '/\((.*) (\d{1,2})\. ([A-Z][a-z]+) ([0-9]{4}), ([0-9]{1,2}):([0-9]{2,2}) - ([0-9]{1,2}):([0-9]{2,2})\)$/';

        preg_match($pattern, $entry->getTitle(), $matches);

        if (9 !== count($matches)) {
            return null;
        }

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
