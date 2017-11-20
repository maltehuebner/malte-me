<?php

namespace AppBundle\Command;

use AppBundle\Model\CriticalmassModel;
use Curl\Curl;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CriticalmassFetchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fahrradstadt:criticalmass:fetch')
            ->setDescription('Fetch ew critical mass ride data')
            ->addArgument(
                'trackId',
                InputArgument::OPTIONAL,
                'Id of the Track to optimize'
            )
            ->addOption('all');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $criticalmass = $this->fetchCriticalmassData();
        $this->cacheCriticalmassData($criticalmass);
    }

    protected function fetchCriticalmassData(): CriticalmassModel
    {
        $curl = new Curl();

        $curl->get('https://criticalmass.in/api/hamburg/current');

        $criticalmass = new CriticalmassModel(new \DateTime(sprintf('@%s', $curl->response->dateTime)), $curl->response->location);

        return $criticalmass;
    }

    protected function cacheCriticalmassData(CriticalmassModel $criticalmass): bool
    {
        $redisConnection = RedisAdapter::createConnection('redis://localhost');

        $cache = new RedisAdapter(
            $redisConnection,
            $namespace = '',
            $defaultLifetime = 0
        );

        $cacheItem = $cache->getItem('criticalmass-view_storage');

        $cacheItem->set($criticalmass);

        return $cache->save($cacheItem);
    }
}
