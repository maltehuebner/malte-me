<?php

namespace AppBundle\Entity;

use AppBundle\Admin\UserAdmin;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="moderated", type="boolean")
     */
    protected $moderated = true;

    /**
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @ORM\Column(name="strava_id", type="string", length=255, nullable=true)
     */
    protected $stravaId;

    /**
     * @ORM\Column(name="strava_access_token", type="string", length=255, nullable=true)
     */
    protected $stravaAccessToken;

    /**
     * @ORM\Column(name="twitter_id", type="string", length=255, nullable=true)
     */
    protected $twitterId;

    /**
     * @ORM\Column(name="twitter_access_token", type="string", length=255, nullable=true)
     */
    protected $twitterAccessToken;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    public function __construct()
    {
        parent::__construct();
    }

    public function setModerated(bool $moderated): User
    {
        $this->moderated = $moderated;

        return $this;
    }

    public function isModerated(): bool
    {
        return $this->moderated;
    }

    public function setFirstname(string $firstname = null): User
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setLastname(string $lastname = null): User
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setFacebookId(string $facebookId): User
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookAccessToken(string $facebookAccessToken): User
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    public function getFacebookAccessToken(): ?string
    {
        return $this->facebookAccessToken;
    }

    public function setStravaId(string $stravaId): User
    {
        $this->stravaId = $stravaId;

        return $this;
    }

    public function getStravaId(): ?string
    {
        return $this->stravaId;
    }

    public function setStravaAccessToken(string $stravaAccessToken): User
    {
        $this->stravaAccessToken = $stravaAccessToken;

        return $this;
    }

    public function getStravaAccessToken(): ?string
    {
        return $this->stravaAccessToken;
    }

    public function setTwitterId(string $twitterId): User
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    public function getTwitterId(): ?string
    {
        return $this->twitterId;
    }

    public function setTwitterAccessToken(string $twitterAccessToken): User
    {
        $this->twitterAccessToken = $twitterAccessToken;

        return $this;
    }

    public function getTwitterAccessToken(): ?string
    {
        return $this->twitterAccessToken;
    }

    public function getDisplayname(): string
    {
        return $this->firstname ?? $this->username;
    }

    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
