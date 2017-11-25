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
                'citySlug',
                InputArgument::REQUIRED,
                'Slug of the city to fetch'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $citySlug = $input->getArgument('citySlug');

        $criticalmass = $this->fetchCriticalmassData($citySlug);
        $this->cacheCriticalmassData($criticalmass);
    }

    protected function fetchCriticalmassData(string $citySlug): CriticalmassModel
    {
        $curl = new Curl();

        $curl->get(sprintf('https://criticalmass.in/api/%s/current', $citySlug));

        $criticalmass = new CriticalmassModel($citySlug, new \DateTime(sprintf('@%s', $curl->response->dateTime)), $curl->response->location);

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

        $cacheItem = $cache->getItem(sprintf('criticalmass-%s', $criticalmass->getCitySlug()));

        $cacheItem->set($criticalmass);

        return $cache->save($cacheItem);
    }
}
