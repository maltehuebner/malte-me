<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use FOS\UserBundle\Model\UserInterface;

class PhotoRepository extends EntityRepository
{
    public function findForFeed(int $limit = 25): array
    {
        $query = $this->getFrontpageQuery();

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findForArchive(): array
    {
        return $this->findForFrontpage();
    }

    public function findForFrontpage(): array
    {
        $query = $this->getFrontpageQuery();

        return $query->getResult();
    }

    public function getFrontpageQuery(UserInterface $user = null): Query
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where($qb->expr()->lte('p.displayDateTime', ':displayDateTime'))
            ->addOrderBy('p.displayDateTime', 'DESC')
            ->setParameter('displayDateTime', new \DateTime())
        ;

        if ($user) {
            $qb
                ->andWhere($qb->expr()->eq('p.user', ':user'))
                ->setParameter('user', $user)
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->andX(
                        $qb->expr()->eq('p.enabled', ':notEnabled'),
                        $qb->expr()->eq('p.imported', ':imported')
                    ),
                    $qb->expr()->eq('p.enabled', ':enabled')
                ))
                ->setParameter('enabled', true)
                ->setParameter('notEnabled', false)
                ->setParameter('imported', true)
            ;
        } else {
            $qb
                ->andWhere($qb->expr()->eq('p.enabled', ':enabled'))
                ->setParameter('enabled', true)
            ;
        }

        return $qb->getQuery();
    }
}
