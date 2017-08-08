<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function findPublicCities(): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->where($qb->expr()->eq('c.publicVisible', ':publicVisible'))
            ->andWhere($qb->expr()->eq('c.enabled', ':enabled'))
            ->orderBy('c.name', 'ASC')
            ->setParameter('publicVisible', true)
            ->setParameter('enabled', true)
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
