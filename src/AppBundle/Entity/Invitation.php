<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invitation")
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdInvitations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="acceptedInvitations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $acceptedBy;

    /**
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $topic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $intro;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $proposedTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $proposedDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $acceptedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id = null): Invitation
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy = null): Invitation
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getAcceptedBy(): ?User
    {
        return $this->acceptedBy;
    }

    public function setAcceptedBy(User $acceptedBy = null): Invitation
    {
        $this->acceptedBy = $acceptedBy;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code = null): Invitation
    {
        $this->code = $code;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic = null): Invitation
    {
        $this->topic = $topic;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro = null): Invitation
    {
        $this->intro = $intro;

        return $this;
    }

    public function getProposedTitle(): ?string
    {
        return $this->proposedTitle;
    }

    public function setProposedTitle(string $proposedTitle = null): Invitation
    {
        $this->proposedTitle = $proposedTitle;

        return $this;
    }

    public function getProposedDescription(): ?string
    {
        return $this->proposedDescription;
    }

    public function setProposedDescription(string $proposedDescription = null): Invitation
    {
        $this->proposedDescription = $proposedDescription;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt = null): Invitation
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAcceptedAt(): \DateTime
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(\DateTime $acceptedAt = null): Invitation
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }

}
