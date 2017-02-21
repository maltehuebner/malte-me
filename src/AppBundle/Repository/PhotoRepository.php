<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PhotoRepository extends EntityRepository
{
    public function findForFrontpage(): array
    {
        $query = $this->getFrontpageQuery();

        return $query->getResult();
    }

    public function getFrontpageQuery(): Query
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where($qb->expr()->eq('p.enabled', true))
            ->andWhere($qb->expr()->lte('p.displayDateTime', ':displayDateTime'))
            ->addOrderBy('p.displayDateTime', 'DESC')
            ->setParameter('displayDateTime', new \DateTime())
        ;

        return $qb->getQuery();
    }
}
