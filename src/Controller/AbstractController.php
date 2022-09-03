<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use IncentiveFactory\Domain\Shared\Command\Command;
use IncentiveFactory\Domain\Shared\Command\CommandBus;
use IncentiveFactory\Domain\Shared\Query\Query;
use IncentiveFactory\Domain\Shared\Query\QueryBus;
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

    public function execute(Command $command): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get(CommandBus::class);
        $commandBus->execute($command);
    }

    public function fetch(Query $query): mixed
    {
        /** @var QueryBus $queryBus */
        $queryBus = $this->container->get(QueryBus::class);

        return $queryBus->fetch($query);
    }
}
