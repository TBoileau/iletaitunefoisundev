<?php

declare(strict_types=1);

namespace App\Security\Http\Action;

use App\Core\Bus\Command\CommandBusInterface;
use App\Core\Bus\Event\EventBusInterface;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use App\Security\Entity\User;
use App\Security\UseCase\Find\Find;
use App\Security\UseCase\Register\Register;
use App\Security\UseCase\Register\Registered;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'register', methods: [Request::METHOD_POST])]
final class RegisterAction implements ActionInterface
{
    public function __invoke(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        EventBusInterface $eventBus,
        Register $register
    ): User {
        $commandBus->dispatch($register);

        /** @var User $user */
        $user = $queryBus->fetch(Find::createFromRegister($register));

        $eventBus->publish(new Registered($user));

        return $user;
    }
}
