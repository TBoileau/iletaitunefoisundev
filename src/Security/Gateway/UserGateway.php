<?php

declare(strict_types=1);

namespace App\Security\Gateway;

use App\Security\Entity\User;

/**
 * @template T
 */
interface UserGateway
{
    public function register(User $user): void;

    public function isUniqueEmail(string $email): bool;
}
