<?php

declare(strict_types=1);

namespace App\Core\Bus\Event;

use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function publish(EventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
