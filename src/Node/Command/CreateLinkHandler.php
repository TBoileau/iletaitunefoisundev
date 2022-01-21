<?php

declare(strict_types=1);

namespace App\Node\Command;

use App\Core\CQRS\HandlerInterface;
use App\Node\Entity\Node;
use App\Node\Gateway\NodeGateway;
use App\Node\Message\Link;

final class CreateLinkHandler implements HandlerInterface
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
