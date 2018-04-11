<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bike_meter_data")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BikeMeterDataRepository")
 */
class BikeMeterData
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BikeMeter", inversedBy="data")
     * @ORM\JoinColumn(name="meter_id", referencedColumnName="id")
     */
    protected $meter;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $value;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dateTime;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): BikeMeterData
    {
        $this->id = $id;

        return $this;
    }

    public function getMeter(): BikeMeter
    {
        return $this->meter;
    }

    public function setMeter(BikeMeter $meter): BikeMeterData
    {
        $this->meter = $meter;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): BikeMeterData
    {
        $this->value = $value;

        return $this;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): BikeMeterData
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): BikeMeterData
    {
        $this->createdAt = $createdAt;

        return $this;
    }




}
