<?php

declare(strict_types=1);

namespace App\Admin\Entity;

use App\Admin\Doctrine\Repository\AdministratorRepository;
use App\Security\Entity\AbstractUser;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: AdministratorRepository::class)]
class Administrator extends AbstractUser
{
    public function getRoles(): array
    {
        return ['ROLE_USER', 'ROLE_ADMIN'];
    }
}
