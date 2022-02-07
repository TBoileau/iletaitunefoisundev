<?php

declare(strict_types=1);

namespace App\Security\UseCase\ResetForgottenPassword;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetForgottenPasswordHandler implements MessageHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(
        private UserGateway $userGateway,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(ResetForgottenPasswordInput $resetForgottenPasswordInput): void
    {
        /** @var User $user */
        $user = $this->userGateway->getUserByForgottenPasswordToken(
            $resetForgottenPasswordInput->forgottenPasswordToken
        );

        $user->setForgottenPasswordToken(null);
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user, $resetForgottenPasswordInput->plainPassword
        ));
        $this->userGateway->update($user);
    }
}
