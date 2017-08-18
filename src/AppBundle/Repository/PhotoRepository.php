<?php

namespace AppBundle\Repository;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use FOS\UserBundle\Model\UserInterface;

class PhotoRepository extends EntityRepository
{
    public function findForFeed(City $city = null, int $limit = 25): array
    {
        $query = $this->getFrontpageQuery($city);

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findForArchive(City $city = null): array
    {
        return $this->findForFrontpage($city);
    }

    public function findForFrontpage(City $city = null): array
    {
        $query = $this->getFrontpageQuery($city);

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

    public function findPreviousPhoto(Photo $photo): ?Photo
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->addOrderBy('p.displayDateTime', 'DESC')
            ->where($qb->expr()->lt('p.displayDateTime', ':displayDateTime'))
            ->setParameter('displayDateTime', $photo->getDisplayDateTime())
            ->andWhere($qb->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->setMaxResults(1)
        ;

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findNextPhoto(Photo $photo): ?Photo
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->addOrderBy('p.displayDateTime', 'ASC')
            ->where($qb->expr()->gt('p.displayDateTime', ':displayDateTime'))
            ->setParameter('displayDateTime', $photo->getDisplayDateTime())
            ->andWhere($qb->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->setMaxResults(1)
        ;

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}
