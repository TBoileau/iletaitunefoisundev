<?php

declare(strict_types=1);

namespace App\Node\Message;

use App\Node\Entity\Node;
use Symfony\Component\Validator\Constraints\NotIdenticalTo;
use Symfony\Component\Validator\Constraints\NotNull;

final class Link
{
    #[NotNull]
    #[NotIdenticalTo(propertyPath: 'to')]
    private Node $from;

    #[NotNull]
    private Node $to;

    public function getFrom(): Node
    {
        return $this->from;
    }

    public function setFrom(Node $from): void
    {
        $this->from = $from;
    }

    public function getTo(): Node
    {
        return $this->to;
    }

    public function setTo(Node $to): void
    {
        $this->to = $to;
    }
}
