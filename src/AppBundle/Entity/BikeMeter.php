<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bike_meter")
 * @ORM\Entity()
 */
class BikeMeter
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="meters")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): BikeMeter
    {
        $this->id = $id;

        return $this;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): BikeMeter
    {
        $this->city = $city;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): BikeMeter
    {
        $this->title = $title;

        return $this;
    }
}
