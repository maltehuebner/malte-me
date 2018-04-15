<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\City;
use AppBundle\Entity\WeatherData;
use AppBundle\Util\DateTimeUtil;
use Doctrine\ORM\EntityRepository;

class WeatherDataRepository extends EntityRepository
{
    public function findForCityDay(City $city, \DateTime $dateTime = null): ?WeatherData
    {
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }

        $beginDateTime = DateTimeUtil::getDayStartDateTime($dateTime);
        $endDateTime = DateTimeUtil::getDayEndDateTime($dateTime);

        $qb = $this->createQueryBuilder('wd');

        $qb
            ->where($qb->expr()->gte('wd.dateTime', ':beginDateTime'))
            ->setParameter('beginDateTime', $beginDateTime)
            ->andWhere($qb->expr()->lte('wd.dateTime', ':endDateTime'))
            ->setParameter('endDateTime', $endDateTime)
            ->andWhere($qb->expr()->eq('wd.city', ':city'))
            ->setParameter('city', $city)
            ->addOrderBy('wd.dateTime', 'DESC')
            ->addOrderBy('wd.createdAt', 'DESC')
            ->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}
