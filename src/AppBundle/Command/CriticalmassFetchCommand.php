<?php

namespace AppBundle\Command;

use AppBundle\Entity\City;
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
            ->setDescription('Fetch new critical mass ride data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $cities = $this->findCitiesWithCriticalmass();

        /** @var City $city */
        foreach ($cities as $city) {
            $criticalmass = $this->fetchCriticalmassData($city);
            $this->cacheCriticalmassData($criticalmass);
        }
    }

    protected function findCitiesWithCriticalmass(): array
    {
        $doctrine = $this->getContainer()->get('doctrine');

        $cities = $doctrine->getRepository(City::class)->findCitiesWithCriticalmass();

        return $cities;
    }

    protected function fetchCriticalmassData(City $city): CriticalmassModel
    {
        $citySlug = $city->getCriticalmassCitySlug();
        
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
