<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\GetMapByRegion;

use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use Symfony\Component\Serializer\Annotation\Groups;

final class Map
{
    /**
     * @var array<int, Quest>
     */
    #[Groups('map')]
    private array $quests = [];

    /**
     * @var array<int, Relation>
     */
    #[Groups('map')]
    private array $relations = [];

    public function __construct(private Region $region, private ?int $firstQuest)
    {
    }

    #[Groups('map')]
    public function getRegion(): Region
    {
        return $this->region;
    }
    
    #[Groups('map')]
    public function getFirstQuest(): ?int
    {
        return $this->firstQuest;
    }

    public function attach(int $from, int $to, string $type): void
    {
        $this->relations[] = new Relation($from, $to, RelationType::from($type));
    }

    public function add(Quest $quest): void
    {
        /** @var int $id */
        $id = $quest->getId();
        if (!$this->has($id)) {
            $this->quests[$id] = $quest;
        }
    }

    public function has(int $id): bool
    {
        return isset($this->quests[$id]);
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
