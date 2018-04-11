<?php declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\BikeMeter\DataFetcher;
use AppBundle\BikeMeter\DataParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BikeMeterCommand extends Command
{
    /** @var DataFetcher $dataFetcher */
    protected $dataFetcher;

    /** @var DataParser $dataParser */
    protected $dataParser;

    public function __construct(?string $name = null, DataFetcher $dataFetcher, DataParser $dataParser)
    {
        $this->dataFetcher = $dataFetcher;

        $this->dataParser = $dataParser;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('fahrradstadt:meter')
            ->setDescription('Refresh meter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->dataFetcher->fetch();

        $this->dataParser->setXmlRootElement($this->dataFetcher->getXmlRootElement());
    }
}
