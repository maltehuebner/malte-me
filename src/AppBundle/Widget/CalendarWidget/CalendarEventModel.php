<?php declare(strict_types=1);

namespace AppBundle\Widget\CalendarWidget;

class CalendarEventModel
{
    /** @var \DateTime $dateTime */
    protected $dateTime;

    /** @var string $title */
    protected $title;

    /** @var string $description */
    protected $description;

    /** @var string $permalink */
    protected $permalink;

    /**
     * @TODO Make optional parameters mandatory again, fix service definitions
     */
    public function __construct(\DateTime $dateTime = null, string $permalink = null, string $title = null, string $description = null)
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
