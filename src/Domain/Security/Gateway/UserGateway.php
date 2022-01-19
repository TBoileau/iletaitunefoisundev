<?php

declare(strict_types=1);

namespace App\Domain\Security\Gateway;

use App\Domain\Security\Entity\User;

/**
 * @template T
 */
interface UserGateway
{
    public function register(User $user): void;

    public function isUniqueEmail(string $email): bool;
}
