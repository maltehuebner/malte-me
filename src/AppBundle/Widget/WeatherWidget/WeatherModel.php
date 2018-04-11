<?php declare(strict_types=1);

namespace AppBundle\Widget\WeatherWidget;

use AppBundle\Entity\City;
use AppBundle\Widget\WidgetDataInterface;

class WeatherModel implements WidgetDataInterface
{
    /** @var City $city */
    protected $city;

    /** @var float $temperaturMin */
    protected $temperaturMin;

    /** @var float $temperaturMax*/
    protected $temperaturMax;

    /** @var float */
    protected $windSpeed;

    /** @var string */
    protected $windDirection;

    /** @var string */
    protected $clouds;

    /** @var string */
    protected $weather;

    public function setCity(City $city): WeatherModel
    {
        $this->city = $city;

        return $this;
    }

    public function getIdentifier(): string
    {
        return sprintf('weather-%s', $this->city->getSlug());
    }

    public function getTemperaturMin(): ?float
    {
        return $this->temperaturMin;
    }

    public function setTemperaturMin(float $temperaturMin = null): WeatherModel
    {
        $this->temperaturMin = $temperaturMin;

        return $this;
    }

    public function getTemperaturMax(): ?float
    {
        return $this->temperaturMax;
    }

    public function setTemperaturMax(float $temperaturMax = null): WeatherModel
    {
        $this->temperaturMax = $temperaturMax;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(float $windSpeed = null): WeatherModel
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWindDirection(): ?string
    {
        return $this->windDirection;
    }

    public function setWindDirection(string $windDirection = null): WeatherModel
    {
        $this->windDirection = $windDirection;

        return $this;
    }

    public function getClouds(): ?string
    {
        return $this->clouds;
    }

     public function setClouds(string $clouds = null): WeatherModel
    {
        $this->clouds = $clouds;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

     public function setWeather(string $weather = null): WeatherModel
    {
        $this->weather = $weather;

        return $this;
    }
}
