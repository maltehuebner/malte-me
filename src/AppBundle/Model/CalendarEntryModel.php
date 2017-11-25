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

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPermalink(): string
    {
        return $this->permalink;
    }
}