<?php

namespace AppBundle\Entity;

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

    public function __construct()
    {
        parent::__construct();
        // your own logic
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
}
