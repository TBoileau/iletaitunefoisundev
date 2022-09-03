<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Messenger;

use IncentiveFactory\Domain\Shared\Command\Command;
use IncentiveFactory\Domain\Shared\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function execute(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
