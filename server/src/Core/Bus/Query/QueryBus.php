<?php

declare(strict_types=1);

namespace App\Core\Bus\Query;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function fetch(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
