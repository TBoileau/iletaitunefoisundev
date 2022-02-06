<?php

declare(strict_types=1);

namespace App\Security\UseCase\RequestForgottenPassword;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use App\Security\Factory\UuidV6Factory;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RequestForgottenPasswordHandler implements MessageHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(
        private UserGateway $userGateway,
        private UserLoaderInterface $userLoader,
        private UuidV6Factory $uuidFactory
    ) {
    }

    public function __invoke(RequestForgottenPasswordInput $requestForgottenPasswordInput): void
    {
        /** @var User $user */
        $user = $this->userLoader->loadUserByIdentifier($requestForgottenPasswordInput->email);
        $user->setForgottenPasswordToken($this->uuidFactory->create()->toRfc4122());
        $this->userGateway->update($user);
    }
}
