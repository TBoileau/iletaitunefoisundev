<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\GetMapByRegion;

use Symfony\Component\Serializer\Annotation\Groups;

final class Relation
{
    #[Groups('read')]
    private int $from;

    #[Groups('read')]
    private int $to;

    #[Groups('read')]
    private RelationType $type;

    public function __construct(int $from, int $to, RelationType $type)
    {
        $this->from = $from;
        $this->to = $to;
        $this->type = $type;
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function getTo(): int
    {
        return $this->to;
    }

    public function getType(): RelationType
    {
        return $this->type;
    }
}
