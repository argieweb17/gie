<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Loads a user by email OR schoolId, allowing students to log in with their Student ID.
 */
class AppUserProvider implements UserProviderInterface
{
    public function __construct(private UserRepository $userRepo) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Try email first
        $user = $this->userRepo->findOneBy(['email' => $identifier]);

        // Then try schoolId
        if (!$user) {
            $user = $this->userRepo->findOneBy(['schoolId' => $identifier]);
        }

        if (!$user) {
            $e = new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
            $e->setUserIdentifier($identifier);
            throw $e;
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}
