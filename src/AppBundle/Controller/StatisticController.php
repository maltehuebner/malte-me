<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\BikeMeter;
use AppBundle\Entity\BikeMeterData;
use AppBundle\Entity\City;
use AppBundle\Entity\WeatherData;
use AppBundle\Model\StatisticModel;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatisticController extends Controller
{
    public function indexAction(Request $request, Registry $registry): Response
    {
        return $this->render('AppBundle:Statistic:index.html.twig');
    }

    protected function buildStats(BikeMeter $bikeMeter, Registry $registry, \DateTime $fromDateTime, \DateTime $untilDateTime): array
    {
        $date = clone $fromDateTime;
        $day = new \DateInterval('P1D');

        $statList = [];

        while ($date <= $untilDateTime) {
            $cyclistSum = $registry->getRepository(BikeMeterData::class)->sumForDay($bikeMeter, $date);

            $weatherDataList = $registry->getRepository(WeatherData::class)->find();
            $temperatureMin = null;
            $temperatureMax = null;
            $rain = 0.0;

            /** @var WeatherData $weatherData */
            foreach ($weatherDataList as $weatherData) {
                if (!$temperatureMin || $weatherData->getTemperatureMin() < $temperatureMin) {
                    $temperatureMin = $weatherData->getTemperatureMin();
                }

                if (!$temperatureMax || $weatherData->getTemperatureMax() > $temperatureMax) {
                    $temperatureMax = $weatherData->getTemperatureMax();
                }

                $rain += $weatherData->getRain();
            }

            $statisticModel = new StatisticModel();
            $statisticModel
                ->setRain($rain)
                ->setMaxTemperature($temperatureMax)
                ->setMinTemperature($temperatureMin)
                ->setSum($cyclistSum);

            $statList[$date->format('U')] = $statisticModel;

            $date->add($day);
        }

        return $statList;
    }
}
