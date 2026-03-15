<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getAccountStatus() === 'pending') {
            throw new CustomUserMessageAccountStatusException(
                'Your account is pending admin approval. Please wait for an administrator to approve your registration.'
            );
        }

        if ($user->getAccountStatus() === 'inactive') {
            throw new CustomUserMessageAccountStatusException(
                'Your account has been deactivated. Please contact an administrator.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user, ?TokenInterface $token = null): void
    {
        // No post-auth checks needed
    }
}
