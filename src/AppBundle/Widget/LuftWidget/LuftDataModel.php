<?php

namespace AppBundle\Widget\LuftWidget;

class LuftDataModel
{
    /** @var \DateTime $dateTime */
    protected $dateTime;

    /** @var string $pollutant */
    protected $pollutant;

    /** @var string $unit */
    protected $unit;

    /** @var int $level */
    protected $level;

    /** @var float $value */
    protected $value;

    public function __construct(\DateTime $dateTime, string $pollutant, string $unit, int $level, float $value)
    {
        $this->dateTime = $dateTime;
        $this->pollutant = $pollutant;
        $this->unit = $unit;
        $this->level = $level;
        $this->value = $value;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getPollutant(): string
    {
        return $this->pollutant;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
