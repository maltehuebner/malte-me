<?php

namespace AppBundle\Model;

class CalendarEntryModel
{
    protected $dateTime;
    protected $title;
    protected $description;
    protected $permalink;

    public function __construct(\DateTime $dateTime, string $permalink, string $title, string $description)
    {
        $this->dateTime = $dateTime;
        $this->permalink = $permalink;
        $this->title = $title;
        $this->description = $description;
    }
}