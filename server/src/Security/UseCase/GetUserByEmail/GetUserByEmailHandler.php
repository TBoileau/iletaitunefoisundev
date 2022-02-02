<?php

declare(strict_types=1);

namespace App\Security\UseCase\GetUserByEmail;

use App\Core\Bus\Query\QueryHandlerInterface;
use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;

final class GetUserByEmailHandler implements QueryHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function __invoke(GetUserByEmail $getUserByEmail): User
    {
        return $this->userGateway->findUserByEmail($getUserByEmail->getEmail());
    }
}
