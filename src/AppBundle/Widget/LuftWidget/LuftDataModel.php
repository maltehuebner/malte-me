<?php declare(strict_types=1);

namespace AppBundle\Widget\LuftWidget;

class LuftDataModel
{
    /** @var \DateTime $dateTime */
    protected $dateTime;

    /** @var string $pollutant */
    protected $pollutant;

    /** @var string $stationCode */
    protected $stationCode;

    /** @var string $stationName */
    protected $stationName;

    /** @var string $unit */
    protected $unit;

    /** @var int $level */
    protected $level;

    /** @var float $value */
    protected $value;

    /**
     * @TODO Make optional parameters mandatory again, fix service definitions
     */
    public function __construct(\DateTime $dateTime = null, string $stationCode = null, string $stationName = null, string $pollutant = null, string $unit = null, int $level = null, float $value = null)
    {
        $this->dateTime = $dateTime;
        $this->stationCode = $stationCode;
        $this->stationName = $stationName;
        $this->pollutant = $pollutant;
        $this->unit = $unit;
        $this->level = $level;
        $this->value = $value;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getStationCode(): string
    {
        return $this->stationCode;
    }

    public function getStationName(): string
    {
        return $this->stationName;
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

    public function getValue(): float
    {
        return $this->value;
    }
}
