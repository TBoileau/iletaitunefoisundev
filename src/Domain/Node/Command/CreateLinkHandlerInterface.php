<?php

declare(strict_types=1);

namespace App\Domain\Node\Command;

use App\Domain\Node\Message\Link;
use App\Domain\Shared\Command\HandlerInterface;

interface CreateLinkHandlerInterface extends HandlerInterface
{
    public function __invoke(Link $link): void;
}
