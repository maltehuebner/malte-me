<?php declare(strict_types=1);

namespace AppBundle\Widget\BikeMeterWidget;

use AppBundle\Widget\WidgetDataInterface;

class BikeMeterDataModel implements WidgetDataInterface
{
    /** @var int $sum */
    protected $sum = 0;

    /** @var int $todaySum */
    protected $todaySum = 0;

    /** @var int $yesterdaySum */
    protected $yesterdaySum = 0;

    /** @var int $daybeforeyesterdaySum */
    protected $daybeforeyesterdaySum = 0;

    public function setSum(int $sum): BikeMeterDataModel
    {
        $this->sum = $sum;

        return $this;
    }

    public function getSum(): int
    {
        return $this->sum;
    }

    public function setTodaySum(int $todaySum): BikeMeterDataModel
    {
        $this->todaySum = $todaySum;

        return $this;
    }

    public function getTodaySum(): int
    {
        return $this->todaySum;
    }

    public function setYesterdaySum(int $yesterdaySum): BikeMeterDataModel
    {
        $this->yesterdaySum = $yesterdaySum;

        return $this;
    }

    public function getYesterdaySum(): int
    {
        return $this->yesterdaySum;
    }

    public function setDaybeforeyesterdaySum(int $daybeforeyesterdaySum): BikeMeterDataModel
    {
        $this->daybeforeyesterdaySum = $daybeforeyesterdaySum;

        return $this;
    }

    public function getDaybeforeyesterdaySum(): int
    {
        return $this->daybeforeyesterdaySum;
    }

    public function getIdentifier(): string
    {
        return 'bike_meter';
    }
}
