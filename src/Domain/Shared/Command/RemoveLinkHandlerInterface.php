<?php

declare(strict_types=1);

namespace App\Domain\Shared\Command;

use App\Domain\Shared\Message\Link;

interface RemoveLinkHandlerInterface extends HandlerInterface
{
    public function __invoke(Link $link): void;
}
