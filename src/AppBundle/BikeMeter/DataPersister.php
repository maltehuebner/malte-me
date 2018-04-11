<?php declare(strict_types=1);

namespace AppBundle\BikeMeter;

use AppBundle\Entity\BikeMeter;
use AppBundle\Entity\BikeMeterData;
use Doctrine\Bundle\DoctrineBundle\Registry;

class DataPersister
{
    /** @var Registry $registry */
    protected $registry;

    /** @var BikeMeter $bikeMeter */
    protected $bikeMeter;

    /** @var array $dataList */
    protected $dataList = [];

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function setBikeMeter(BikeMeter $bikeMeter): DataPersister
    {
        $this->bikeMeter = $bikeMeter;

        return $this;
    }

    public function setDataList(array $dataList): DataPersister
    {
        $this->dataList = $dataList;

        ksort($this->dataList);

        return $this;
    }

    public function save(): DataPersister
    {
        $oldValues = $this->getExistingDataValues();
        $this->removeExistingValuesFromList($oldValues);

        $this->persist();

        $this->registry->getManager()->flush();

        return $this;
    }

    protected function getExistingDataValues(): array
    {
        $list = $this->dataList;

        /** @var BikeMeterData $first */
        $first = array_shift($list);

        /** @var BikeMeterData $last */
        $last = array_pop($list);

        return $this->registry->getRepository(BikeMeterData::class)->findBetween($this->bikeMeter, $first->getDateTime(), $last->getDateTime());
    }

    protected function removeExistingValuesFromList(array $existingValues): DataPersister
    {
        foreach ($existingValues as $existingValue) {
            $timestamp = $existingValue->getDateTime()->format('U');

            if (array_key_exists($timestamp, $this->dataList)) {
                unset($this->dataList[$timestamp]);
            }
        }

        return $this;
    }

    protected function persist(): DataPersister
    {
        $em = $this->registry->getManager();

        foreach ($this->dataList as $data) {
            $em->persist($data);
        }

        return $this;
    }
}
