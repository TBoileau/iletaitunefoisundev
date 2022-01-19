<?php

declare(strict_types=1);

namespace App\Domain\Security\Command;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Domain\Security\Message\Registration;
use App\Domain\Shared\Uuid\UuidGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationHandler implements RegistrationHandlerInterface
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private UserGateway $userGateway,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(Registration $registration): void
    {
        $user = new User();
        $user->setId($this->uuidGenerator->generate());
        $user->setEmail($registration->getEmail());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $registration->getPlainPassword()));
        $this->userGateway->register($user);
    }
}
