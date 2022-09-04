<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\EventDispatcher;

use IncentiveFactory\Domain\Shared\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class DecoratedEventDispatcher implements EventDispatcher
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatch(object $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
