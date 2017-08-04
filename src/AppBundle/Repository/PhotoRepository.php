<?php

namespace AppBundle\Repository;

use AppBundle\Entity\City;
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

    public function getFrontpageQuery(City $city = null): Query
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where($qb->expr()->lte('p.displayDateTime', ':displayDateTime'))
            ->andWhere($qb->expr()->eq('p.enabled', ':enabled'))
            ->addOrderBy('p.displayDateTime', 'DESC')
            ->setParameter('displayDateTime', new \DateTime())
            ->setParameter('enabled', true)
        ;

        if ($city) {
            $qb
                ->innerJoin('p.cities', 'c')
                ->andWhere($qb->expr()->eq('c', ':city'))
                ->setParameter('city', $city)
            ;
        }

        return $qb->getQuery();
    }

    public function getImportedPhotosQuery(UserInterface $user): Query
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->addOrderBy('p.displayDateTime', 'DESC')
            ->where($qb->expr()->eq('p.user', ':user'))
            ->andWhere($qb->expr()->eq('p.enabled', ':notEnabled'))
            ->andWhere($qb->expr()->eq('p.imported', ':imported'))
            ->setParameter('user', $user)
            ->setParameter('notEnabled', false)
            ->setParameter('imported', true)
        ;

        return $qb->getQuery();
    }

    public function findImportedPhotos(UserInterface $user): array
    {
        $query = $this->getImportedPhotosQuery($user);

        return $query->getResult();
    }

}
