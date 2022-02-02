<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Security\Doctrine\Repository\UserRepository;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: UserRepository::class)]
class User extends AbstractUser
{
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }
}
