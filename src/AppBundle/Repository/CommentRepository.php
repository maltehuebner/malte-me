<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use AppBundle\Entity\User;
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
}
