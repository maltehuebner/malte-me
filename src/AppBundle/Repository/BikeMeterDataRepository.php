<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Util\DateTimeUtil;
use Doctrine\ORM\EntityRepository;

class BikeMeterDataRepository extends EntityRepository
{
    public function countForDay(\DateTime $dateTime): int
    {
        $beginDateTime = DateTimeUtil::getDayStartDateTime($dateTime);
        $endDateTime = DateTimeUtil::getDayEndDateTime($dateTime);

        $qb = $this->createQueryBuilder('bmd');

        $qb
            ->where($qb->expr()->gte('bmd.dateTime', ':beginDateTime'))
            ->setParameter('beginDateTime', $beginDateTime)
            ->andWhere($qb->expr()->lte('bmd.dateTime', ':endDateTime'))
            ->setParameter('endDateTime', $endDateTime)
            ->select('SUM(bmd.value) AS bikeMeterValue');

        $query = $qb->getQuery();

        return intval($query->getSingleScalarResult());
    }
}
