<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Core\Bus\Event\EventInterface;
use App\Security\Entity\User;

final class Registered implements EventInterface
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
