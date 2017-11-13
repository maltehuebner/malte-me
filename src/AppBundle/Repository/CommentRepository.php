<?php

namespace AppBundle\Repository;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function findForPhoto(Photo $photo): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->where($qb->expr()->eq('c.photo', ':photo'))
            ->andWhere($qb->expr()->eq('c.enabled', ':enabled'))
            ->orderBy('c.dateTime', 'ASC')
            ->setParameter('photo', $photo)
            ->setParameter('enabled', true)
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findLatest(City $city, int $max = 10): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->join('c.photo', 'p')
            ->join('p.cities', 'cs')
            ->where($qb->expr()->eq('c.enabled', ':enabled'))
            ->andWhere($qb->expr()->in('cs', ':city'))
            ->orderBy('c.dateTime', 'DESC')
            ->setParameter('enabled', true)
            ->setParameter('city', $city)
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
