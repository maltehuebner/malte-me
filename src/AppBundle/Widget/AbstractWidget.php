<?php declare(strict_types=1);

namespace AppBundle\Widget;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

abstract class AbstractWidget implements WidgetInterface
{
    /** @var Doctrine $doctrine */
    protected $doctrine;

    /** @var EngineInterface $twigEngine */
    protected $twigEngine;

    public function __construct(Doctrine $doctrine, EngineInterface $twigEngine)
    {
        $this->doctrine = $doctrine;
        $this->twigEnginge = $twigEngine;
    }

    protected function retrieveData(string $slug): ?WidgetDataInterface
    {
        $redisConnection = RedisAdapter::createConnection('redis://localhost');

        $cache = new RedisAdapter(
            $redisConnection,
            $namespace = '',
            $defaultLifetime = 0
        );

        $cacheItem = $cache->getItem($slug);

        return $cacheItem->get();
    }
}
