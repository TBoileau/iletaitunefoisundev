<?php

declare(strict_types=1);

namespace App\Security\UseCase\GetUser;

use App\Core\Bus\Query\QueryHandlerInterface;
use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;

final class GetUserHandler implements QueryHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function __invoke(GetUser $find): User
    {
        return $this->userGateway->findUserByEmail($find->getEmail());
    }
}
