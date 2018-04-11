<?php declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\BikeMeter\DataFetcher;
use AppBundle\BikeMeter\DataParser;
use AppBundle\Entity\BikeMeter;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BikeMeterCommand extends Command
{
    /** @var DataFetcher $dataFetcher */
    protected $dataFetcher;

    /** @var DataParser $dataParser */
    protected $dataParser;

    /** @var Registry $registry */
    protected $registry;

    public function __construct(?string $name = null, DataFetcher $dataFetcher, DataParser $dataParser, Registry $registry)
    {
        $this->dataFetcher = $dataFetcher;

        $this->dataParser = $dataParser;

        $this->registry = $registry;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('fahrradstadt:meter')
            ->setDescription('Refresh meter');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $bikeMeter = $this->registry->getRepository(BikeMeter::class)->find(1);

        $this->dataFetcher->fetch();

        $list = $this->dataParser
            ->setTimezone(new \DateTimeZone('Europe/Berlin'))
            ->setBikeMeter($bikeMeter)
            ->setXmlRootElement($this->dataFetcher->getXmlRootElement())
            ->parse()
            ->getDataList();

        $em = $this->registry->getManager();

        foreach ($list as $item) {
            $em->persist($item);
        }

        $em->flush();
    }
}
