<?php declare(strict_types=1);

namespace AppBundle\Model;

class StatisticModel
{
    /** @var \DateTime $date */
    protected $date;

    /** @var int $sum */
    protected $sum;

    /** @var float $minTemperature */
    protected $minTemperature;

    /** @var float $maxTemperature */
    protected $maxTemperature;

    /** @var float $rain */
    protected $rain;

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return StatisticModel
     */
    public function setDate(\DateTime $date): StatisticModel
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getSum(): int
    {
        return $this->sum;
    }

    /**
     * @param int $sum
     * @return StatisticModel
     */
    public function setSum(int $sum): StatisticModel
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * @return float
     */
    public function getMinTemperature(): float
    {
        return $this->minTemperature;
    }

    /**
     * @param float $minTemperature
     * @return StatisticModel
     */
    public function setMinTemperature(float $minTemperature): StatisticModel
    {
        $this->minTemperature = $minTemperature;
        return $this;
    }

    /**
     * @return float
     */
    public function getMaxTemperature(): float
    {
        return $this->maxTemperature;
    }

    /**
     * @param float $maxTemperature
     * @return StatisticModel
     */
    public function setMaxTemperature(float $maxTemperature): StatisticModel
    {
        $this->maxTemperature = $maxTemperature;
        return $this;
    }

    public function getRain(): float
    {
        return $this->rain;
    }

    public function setRain(float $rain): StatisticModel
    {
        $this->rain = $rain;

        return $this;
    }
}
