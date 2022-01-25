<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Security\Repository\AdministratorRepository;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: AdministratorRepository::class)]
class Administrator extends AbstractUser
{
    public function getRoles(): array
    {
        return ['ROLE_USER', 'ROLE_ADMIN'];
    }
}
