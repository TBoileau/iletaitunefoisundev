<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\GetMapByRegion;

use App\Adventure\Entity\Quest;
use Symfony\Component\Serializer\Annotation\Groups;

final class Map
{
    /**
     * @var array<int, Quest>
     */
    #[Groups('read')]
    private array $quests = [];

    /**
     * @var array<int, Relation>
     */
    #[Groups('read')]
    private array $relations = [];

    public function __construct(private ?int $firstQuest)
    {
    }

    #[Groups('read')]
    public function getFirstQuest(): ?int
    {
        return $this->firstQuest;
    }

    public function attach(int $from, int $to, string $type): void
    {
        if (!isset($this->relations[$from])) {
            $this->relations[$from] = [];
        }


        $this->relations[$from][] = new Relation($from, $to, RelationType::from($type));
    }

    public function add(Quest $quest): void
    {
        if (!$this->has($quest->getId())) {
            $this->quests[$quest->getId()] = $quest;
        }
    }

    public function has(int $id): bool
    {
        return isset($quests[$id]);
    }

    /**
     * @return array<int, Quest>
     */
    public function getQuests(): array
    {
        return $this->quests;
    }

    /**
     * @return array<int, Relation>
     */
    public function getRelations(): array
    {
        return $this->relations;
    }
}
