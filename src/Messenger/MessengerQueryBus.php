<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Messenger;

use IncentiveFactory\Domain\Shared\Query\Query;
use IncentiveFactory\Domain\Shared\Query\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function fetch(Query $query): mixed
    {
        return $this->handle($query);
    }
}
