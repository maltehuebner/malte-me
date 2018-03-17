<?php declare(strict_types=1);

namespace AppBundle\Widget\CriticalmassWidget;

use AppBundle\Widget\WidgetDataInterface;

class CriticalmassModel implements WidgetDataInterface
{
    /** @var string $citySlug */
    protected $citySlug;

    /** @var \DateTime $dateTime */
    protected $dateTime;

    /** @var string $location */
    protected $location;

    /**
     * @TODO Make optional parameters mandatory again, fix service definitions
     */
    public function __construct(string $citySlug = null, \DateTime $dateTime = null, string $location = null)
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

    public function getIdentifier(): string
    {
        return sprintf('criticalmass-%s', $this->citySlug);
    }
}
