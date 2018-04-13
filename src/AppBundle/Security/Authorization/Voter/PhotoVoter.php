<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Photo;
use AppBundle\Entity\User;

class PhotoVoter extends AbstractVoter
{
    protected function canView(Photo $photo, User $user = null): bool
    {
        return true;
    }

    protected function canFavorite(Photo $photo, User $user): bool
    {
        return true;
    }

    protected function canComment(Photo $photo, User $user): bool
    {
        return true;
    }

    protected function canEdit(Photo $photo, User $user): bool
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        if ($user === $photo->getUser()) {
            return true;
        }

        return false;
    }

    protected function canManipulate(Photo $photo, User $user): bool
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        if ($user === $photo->getUser()) {
            return true;
        }

        return false;
    }
}
