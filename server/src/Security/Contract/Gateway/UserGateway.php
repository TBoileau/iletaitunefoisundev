<?php

declare(strict_types=1);

namespace App\Security\Contract\Gateway;

use App\Security\Entity\User;
use Doctrine\ORM\QueryBuilder;

/**
 * @template T
 */
interface UserGateway
{
    public function register(User $user): void;

    public function isUniqueEmail(string $email): bool;

    public function createQueryBuilderUsersWhoHaveNotCreatedTheirPlayer(): QueryBuilder;
}
