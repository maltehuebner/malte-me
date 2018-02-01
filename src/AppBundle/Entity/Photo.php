<?php

namespace AppBundle\Entity;

use AppBundle\Share\ShareableInterface\Shareable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Share\Annotation as Sharing;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotoRepository")
 * @ORM\Table(name="photo")
 * @Vich\Uploadable
 * @Sharing\Route(route="show_photo")
 */
class Photo implements Shareable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="photos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="Invitation", mappedBy="photo")
     * @ORM\JoinColumn(name="invitation_id", referencedColumnName="id")
     */
    protected $invitation;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="photo")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Favorite", mappedBy="photo")
     */
    protected $favorites;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    protected $location;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Sharing\RouteParameter(name="slug")
     */
    protected $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    protected $source;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled = true;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $highlighted = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $sponsored = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $affiliated = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $imported = false;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dateTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $displayDateTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @Vich\UploadableField(mapping="photo", fileNameProperty="imageName")
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $imageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $backupName;

    /**
     * @ORM\ManyToMany(targetEntity="City", mappedBy="photos")
     */
    protected $cities;

    public function __construct()
    {
        $this->dateTime = new \DateTime();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->description = '';

        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->cities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user = null): Photo
    {
        $this->user = $user;

        return $this;
    }

    public function getInvitation(): ?Invitation
    {
        return $this->invitation;
    }

    public function setInvitation(Invitation $invitation = null): Photo
    {
        $invitation->setPhoto($this);

        $this->invitation = $invitation;

        return $this;
    }

    public function addComment(Comment $comment): Photo
    {
        $this->comments->add($comment);

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function removeComment(Comment $comment): Photo
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    public function addFavorite(Favorite $favorites): Photo
    {
        $this->favorites->add($favorites);

        return $this;
    }

    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function removeFavorite(Favorite $favorites): Photo
    {
        $this->favorites->removeElement($favorites);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Photo
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): Photo
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): Photo
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): Photo
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): Photo
    {
        $this->location = $location;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Photo
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): Photo
    {
        $this->description = $description;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source = null): Photo
    {
        $this->source = $source;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\Datetime $dateTime): Photo
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getDisplayDateTime(): ?\DateTime
    {
        return $this->displayDateTime;
    }

    public function setDisplayDateTime(\DateTime $displayDateTime): Photo
    {
        $this->displayDateTime = $displayDateTime;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): Photo
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setImageFile(File $image = null): Photo
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(string $imageName = null): Photo
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setUpdatedAt(\DateTime $updatedAt): Photo
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setSponsored(bool $sponsored): Photo
    {
        $this->sponsored = $sponsored;

        return $this;
    }

    public function getSponsored(): bool
    {
        return $this->sponsored;
    }

    public function setHighlighted(bool $highlighted): Photo
    {
        $this->highlighted = $highlighted;

        return $this;
    }

    public function getHighlighted(): bool
    {
        return $this->highlighted;
    }

    public function setAffiliated(bool $affiliated): Photo
    {
        $this->affiliated = $affiliated;

        return $this;
    }

    public function getAffiliated(): bool
    {
        return $this->affiliated;
    }

    public function setImported(bool $imported): Photo
    {
        $this->imported = $imported;

        return $this;
    }

    public function getImported(): bool
    {
        return $this->imported;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->title, $this->user ? $this->user->getUsername() : '');
    }

    public function getPin(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude . ',' . $this->longitude;
        }

        return null;
    }

    public function addCity(City $city): Photo
    {
        $this->cities->add($city);

        return $this;
    }

    public function setCities(Collection $cities): Photo
    {
        $this->cities = $cities;

        return $this;
    }

    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function removeCity(City $city): Photo
    {
        $this->cities->removeElement($city);

        return $this;
    }

    public function setBackupName(string $backupName): Photo
    {
        $this->backupName = $backupName;

        return $this;
    }

    public function getBackupName(): ?string
    {
        return $this->backupName;
    }
}
