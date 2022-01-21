<?php

declare(strict_types=1);

namespace App\Domain\Security\Entity;

use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: UserRepository::class)]
class User extends AbstractUser
{
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
}
