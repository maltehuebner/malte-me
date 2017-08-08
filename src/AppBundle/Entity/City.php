<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 * @Vich\Uploadable
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $hostname;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $seoDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled = true;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $missionText;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $showMenuMission = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $showMenuUpload = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publicVisible = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $archiveIntroText;

    /**
     * @var Photo[]
     *
     * @ORM\ManyToMany(targetEntity="Photo", inversedBy="cities")
     * @ORM\JoinTable(
     *  name="photo_city",
     *  joinColumns={
     *      @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): City
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): City
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): City
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): City
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): City
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(string $seoDescription): City
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): City
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(\DateTime $updatedAt): City
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setSponsored(bool $sponsored): City
    {
        $this->sponsored = $sponsored;

        return $this;
    }

    public function addPhoto(Photo $photo): City
    {
        $this->photos->add($photo);

        return $this;
    }

    public function setPhotos(Collection $photos): City
    {
        $this->photos = $photos;

        return $this;
    }

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function removePhoto(Photo $photo): City
    {
        $this->photos->removeElement($photo);

        return $this;
    }

    public function getMissionText(): ?string
    {
        return $this->missionText;
    }

    public function setMissionText(string $missionText = null): City
    {
        $this->missionText = $missionText;

        return $this;
    }

    public function getShowMenuMission(): bool
    {
        return $this->showMenuMission;
    }

    public function setShowMenuMission(bool $showMenuMission): City
    {
        $this->showMenuMission = $showMenuMission;

        return $this;
    }

    public function getShowMenuUpload(): bool
    {
        return $this->showMenuUpload;
    }

    public function setShowMenuUpload(bool $showMenuUpload): City
    {
        $this->showMenuUpload = $showMenuUpload;

        return $this;
    }

    public function getPublicVisible(): bool
    {
        return $this->publicVisible;
    }

    public function setPublicVisible(bool $publicVisible): City
    {
        $this->publicVisible = $publicVisible;

        return $this;
    }

    public function getArchiveIntroText(): ?string
    {
        return $this->archiveIntroText;
    }

    public function setArchiveIntroText(string $archiveIntroText): City
    {
        $this->archiveIntroText = $archiveIntroText;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
