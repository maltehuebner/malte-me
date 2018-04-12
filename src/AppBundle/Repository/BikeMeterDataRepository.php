<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\BikeMeter;
use AppBundle\Util\DateTimeUtil;
use Doctrine\ORM\EntityRepository;

class BikeMeterDataRepository extends EntityRepository
{
    public function sumForDay(BikeMeter $bikeMeter, \DateTime $dateTime): int
    {
        $beginDateTime = DateTimeUtil::getDayStartDateTime($dateTime);
        $endDateTime = DateTimeUtil::getDayEndDateTime($dateTime);

        $qb = $this->createQueryBuilder('bmd');

        $qb
            ->select('SUM(bmd.value) AS bikeMeterValue')
            ->where($qb->expr()->gte('bmd.dateTime', ':beginDateTime'))
            ->setParameter('beginDateTime', $beginDateTime)
            ->andWhere($qb->expr()->lte('bmd.dateTime', ':endDateTime'))
            ->setParameter('endDateTime', $endDateTime)
            ->andWhere($qb->expr()->eq('bmd.meter', ':meter'))
            ->setParameter('meter', $bikeMeter);

        $query = $qb->getQuery();

        return intval($query->getSingleScalarResult());
    }

    public function sum(BikeMeter $bikeMeter): int
    {
        $qb = $this->createQueryBuilder('bmd');

        $qb
            ->select('SUM(bmd.value) AS bikeMeterValue')
            ->where($qb->expr()->eq('bmd.meter', ':meter'))
            ->setParameter('meter', $bikeMeter);

        $query = $qb->getQuery();

        return intval($query->getSingleScalarResult());
    }

    public function findBetween(BikeMeter $bikeMeter, \DateTime $beginDateTime, \DateTime $endDateTime): array
    {
        $qb = $this->createQueryBuilder('bmd');

        $qb
            ->where($qb->expr()->gte('bmd.dateTime', ':beginDateTime'))
            ->setParameter('beginDateTime', $beginDateTime)
            ->andWhere($qb->expr()->lte('bmd.dateTime', ':endDateTime'))
            ->setParameter('endDateTime', $endDateTime)
            ->andWhere($qb->expr()->eq('bmd.meter', ':meter'))
            ->setParameter('meter', $bikeMeter);

        $query = $qb->getQuery();

        return $query->getResult();
    }


}
