<?php

declare(strict_types=1);

namespace App\Security\UseCase\Find;

use App\Core\Bus\Query\QueryHandlerInterface;
use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;

final class FindHandler implements QueryHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function __invoke(Find $find): User
    {
        return $this->userGateway->findUserByEmail($find->getEmail());
    }
}
