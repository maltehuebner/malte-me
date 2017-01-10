<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PhotoRepository extends EntityRepository
{
    public function findForFrontpage(): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('p.enabled', true));

        $qb->addOrderBy('p.dateTime', 'DESC');

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
