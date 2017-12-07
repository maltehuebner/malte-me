<?php

namespace AppBundle\Widget;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

abstract class AbstractWidgetFactory implements WidgetFactoryInterface
{
    /** @var Doctrine $doctrine */
    protected $doctrine;

    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    protected function cacheData(WidgetDataInterface $data): bool
    {
        $redisConnection = RedisAdapter::createConnection('redis://localhost');

        $cache = new RedisAdapter(
            $redisConnection,
            $namespace = '',
            $defaultLifetime = 0
        );

        $cacheItem = $cache->getItem($data->getIdentifier());

        $cacheItem->set($data);

        return $cache->save($cacheItem);
    }
}
