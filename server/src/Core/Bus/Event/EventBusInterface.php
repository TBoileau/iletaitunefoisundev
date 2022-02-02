<?php

declare(strict_types=1);

namespace App\Core\Bus\Event;

interface EventBusInterface
{
    public function publish(EventInterface $event): void;
}
