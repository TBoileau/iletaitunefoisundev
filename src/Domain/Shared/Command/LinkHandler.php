<?php

declare(strict_types=1);

namespace App\Domain\Shared\Command;

use App\Domain\Shared\Gateway\NodeGateway;
use App\Domain\Shared\Message\Link;

final class LinkHandler implements LinkHandlerInterface
{
    public function __construct(private NodeGateway $nodeGateway)
    {
    }

    public function __invoke(Link $link): void
    {
        $link->getFrom()->addSibling($link->getTo());
        $this->nodeGateway->update();
    }
}
