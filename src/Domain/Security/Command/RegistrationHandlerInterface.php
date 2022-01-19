<?php

declare(strict_types=1);

namespace App\Domain\Security\Command;

use App\Domain\Security\Message\Registration;

interface RegistrationHandlerInterface
{
    public function __invoke(Registration $registration): void;
}
