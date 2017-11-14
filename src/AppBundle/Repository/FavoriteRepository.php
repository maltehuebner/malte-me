<?php

namespace AppBundle\Repository;

use AppBundle\Entity\City;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class FavoriteRepository extends EntityRepository
{
    public function findForUserAndPhoto(User $user, Photo $photo): ?Favorite
    {
        $qb = $this->createQueryBuilder('f');

        $qb
            ->where($qb->expr()->eq('f.user', ':user'))
            ->andWhere($qb->expr()->eq('f.photo', ':photo'))
            ->setMaxResults(1)
            ->setParameter('user', $user)
            ->setParameter('photo', $photo)
        ;

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findForUser(User $user): array
    {
        $qb = $this->createQueryBuilder('f');

        $qb
            ->join('f.photo', 'p')
            ->where($qb->expr()->eq('f.user', ':user'))
            ->setParameter('user', $user)
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
            ->setMaxResults($max)
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
