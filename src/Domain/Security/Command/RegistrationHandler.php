<?php

declare(strict_types=1);

namespace App\Domain\Security\Command;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Domain\Security\Message\Registration;
use App\Domain\Shared\Command\HandlerInterface;
use App\Domain\Shared\Uuid\UlidGeneratorInterface;
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

    public function __invoke(Registration $registration): void
    {
        $user = new User();
        $user->setId($this->ulidGenerator->generate());
        $user->setEmail($registration->getEmail());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $registration->getPlainPassword()));
        $this->userGateway->register($user);
    }
}
