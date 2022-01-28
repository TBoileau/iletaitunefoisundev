<?php

declare(strict_types=1);

namespace App\Security\Command;

use App\Core\CQRS\HandlerInterface;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Entity\User;
use App\Security\Gateway\UserGateway;
use App\Security\Message\Registration;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationHandler implements HandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(
        private UlidGeneratorInterface $ulidGenerator,
        private UserGateway $userGateway,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(Registration $registration): User
    {
        $user = new User();
        $user->setId($this->ulidGenerator->generate());
        $user->setEmail($registration->getEmail());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $registration->getPlainPassword()));
        $this->userGateway->register($user);

        return $user;
    }
}
