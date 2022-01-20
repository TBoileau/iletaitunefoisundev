<?php

declare(strict_types=1);

namespace App\Domain\Node\Command;

use App\Domain\Node\Entity\Node;
use App\Domain\Node\Gateway\NodeGateway;
use App\Domain\Node\Message\Link;

final class CreateLinkHandler implements CreateLinkHandlerInterface
{
    /**
     * @param NodeGateway<Node> $nodeGateway
     */
    public function __construct(private NodeGateway $nodeGateway)
    {
    }

    public function __invoke(Link $link): void
    {
        $link->getFrom()->addSibling($link->getTo());
        $this->nodeGateway->update();
    }
}