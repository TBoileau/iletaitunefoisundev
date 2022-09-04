<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Security\Provider;

use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', $user::class));
        }

        $player = $this->playerGateway->getPlayerByEmail($user->getUserIdentifier());

        if (null === $player) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $user->getUserIdentifier()));
        }

        return new User($player);
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $player = $this->playerGateway->getPlayerByEmail($identifier);

        if (null === $player) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
        }

        return new User($player);
    }
}
