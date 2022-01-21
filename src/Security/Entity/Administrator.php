<?php

declare(strict_types=1);

namespace App\Security\Entity;

use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Administrator extends AbstractUser
{
    public function getRoles(): array
    {
        return ['ROLE_USER', 'ROLE_ADMIN'];
    }
}
