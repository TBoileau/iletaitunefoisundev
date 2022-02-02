<?php

declare(strict_types=1);

namespace App\Security\ViewModel;

use App\Security\Entity\User;

final class UserViewModel
{
    private function __construct(public string $id, public string $email)
    {
    }

    public static function createFromUser(User $user): UserViewModel
    {
        return new self((string) $user->getId(), $user->getEmail());
    }
}
