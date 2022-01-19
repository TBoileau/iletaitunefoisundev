<?php

declare(strict_types=1);

namespace App\Domain\Security\Gateway;

use App\Domain\Security\Entity\User;

interface UserGateway
{
    public function register(User $user): void;
}
