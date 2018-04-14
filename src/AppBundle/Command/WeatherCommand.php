<?php declare(strict_types=1);

namespace AppBundle\Command;

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
        $this->dataFetcher = $weatherRetriever;

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
    }
}
