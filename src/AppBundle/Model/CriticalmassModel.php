<?php

namespace AppBundle\Model;

class CriticalmassModel
{
    /** @var string $citySlug */
    protected $citySlug;

    /** @var \DateTime $dateTime */
    protected $dateTime;

    /** @var string $location */
    protected $location;

    public function __construct(string $citySlug, \DateTime $dateTime, string $location = null)
    {
        $this->citySlug = $citySlug;
        $this->dateTime = $dateTime;
        $this->location = $location;
    }

    public function getCitySlug(): string
    {
        return $this->citySlug;
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