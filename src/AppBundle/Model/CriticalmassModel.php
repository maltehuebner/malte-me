<?php

namespace AppBundle\Model;

class CriticalmassModel
{
    /** @var \DateTime $dateTime */
    protected $dateTime;

    /** @var string $location */
    protected $location;

    public function __construct(\DateTime $dateTime, string $location = null)
    {
        $this->dateTime = $dateTime;
        $this->location = $location;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }
}