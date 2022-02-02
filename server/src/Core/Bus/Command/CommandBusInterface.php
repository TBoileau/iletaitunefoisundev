<?php

declare(strict_types=1);

namespace App\Core\Bus\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
