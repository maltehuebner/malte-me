<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Photo;
use AppBundle\Entity\User;

class PhotoVoter extends AbstractVoter
{
    protected function canView(Photo $photo, User $user): int
    {
        return self::ACCESS_GRANTED;
    }

    protected function canFavorite(Photo $photo, User $user): int
    {
        return self::ACCESS_GRANTED;
    }

    protected function canComment(Photo $photo, User $user): int
    {
        return self::ACCESS_GRANTED;
    }

    protected function canEdit(Photo $photo, User $user): int
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return self::ACCESS_GRANTED;
        }

        if ($user === $photo->getUser()) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }

    protected function canManipulate(Photo $photo, User $user): int
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return self::ACCESS_GRANTED;
        }

        if ($user === $photo->getUser()) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
