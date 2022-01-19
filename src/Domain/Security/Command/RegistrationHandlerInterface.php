<?php

declare(strict_types=1);

namespace App\Domain\Security\Command;

use App\Domain\Security\Message\Registration;
use App\Domain\Shared\Command\HandlerInterface;

interface RegistrationHandlerInterface extends HandlerInterface
{
    public function __invoke(Registration $registration): void;
}
