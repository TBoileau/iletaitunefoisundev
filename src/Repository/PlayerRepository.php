<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Repository;

use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;

final class PlayerRepository implements PlayerGateway
{
    public function register(Player $player): void
    {
        // TODO: Implement register() method.
    }

    public function hasEmail(string $email, ?Player $player = null): bool
    {
        // TODO: Implement hasEmail() method.
        return false;
    }

    public function hasRegistrationToken(string $registrationToken): bool
    {
        // TODO: Implement hasRegistrationToken() method.
        return false;
    }

    public function findOneByEmail(string $email): ?Player
    {
        // TODO: Implement findOneByEmail() method.
        return null;
    }

    public function findOneByRegistrationToken(string $registrationToken): ?Player
    {
        // TODO: Implement findOneByRegistrationToken() method.
        return null;
    }

    public function findOneByForgottenPasswordToken(string $forgottenPasswordToken): ?Player
    {
        // TODO: Implement findOneByForgottenPasswordToken() method.
        return null;
    }

    public function update(Player $player): void
    {
        // TODO: Implement update() method.
    }
}
