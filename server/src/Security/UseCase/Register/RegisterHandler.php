<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterHandler implements MessageHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(
        private UserGateway $userGateway,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(RegisterInput $register): User
    {
        $user = new User();
        $user->setEmail($register->email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $register->plainPassword));
        $this->userGateway->register($user);

        return $user;
    }
}
