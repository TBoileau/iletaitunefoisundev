<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Core\Bus\Command\CommandHandlerInterface;
use App\Core\Bus\Event\EventBusInterface;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterHandler implements CommandHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(
        private UlidGeneratorInterface $ulidGenerator,
        private UserGateway $userGateway,
        private EventBusInterface $eventBus,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(Register $register): void
    {
        $user = new User();
        $user->setId($this->ulidGenerator->generate());
        $user->setEmail($register->getEmail());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $register->getPlainPassword()));
        $this->userGateway->register($user);
        $this->eventBus->publish(new Registered($user));
    }
}
