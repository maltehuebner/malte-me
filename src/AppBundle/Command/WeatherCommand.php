<?php declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Entity\City;
use AppBundle\Weather\Retriever\WeatherRetriever;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WeatherCommand extends Command
{
    /** @var WeatherRetriever $weatherRetriever */
    protected $weatherRetriever;

    /** @var Registry $registry */
    protected $registry;

    public function __construct(?string $name = null, WeatherRetriever $weatherRetriever, Registry $registry)
    {
        $this->weatherRetriever = $weatherRetriever;

        $this->registry = $registry;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('fahrradstadt:weather')
            ->setDescription('Refresh weather data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $cityList = $this->registry->getRepository(City::class)->findAll();

        /** @var City $city */
        foreach ($cityList as $city) {
            if ($city->getLatitude() && $city->getLongitude()) {
                $weather = $this->weatherRetriever->setCity($city)->fetch()->getWeatherData();

                $this->registry->getManager()->persist($weather);
            }
        }

        $this->registry->getManager()->flush();
    }
}
