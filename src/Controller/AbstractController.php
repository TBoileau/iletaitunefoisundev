<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Shared\Command\Command;
use IncentiveFactory\Domain\Shared\Command\CommandBus;
use IncentiveFactory\Domain\Shared\Query\Query;
use IncentiveFactory\Domain\Shared\Query\QueryBus;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;

abstract class AbstractController extends SymfonyController
{
    public static function getSubscribedServices(): array
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                CommandBus::class,
                QueryBus::class,
            ]
        );
    }

    protected function getPlayer(): ?Player
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user->player;
    }

    protected function execute(Command $command): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get(CommandBus::class);
        $commandBus->execute($command);
    }

    protected function fetch(Query $query): mixed
    {
        /** @var QueryBus $queryBus */
        $queryBus = $this->container->get(QueryBus::class);

        return $queryBus->fetch($query);
    }
}
