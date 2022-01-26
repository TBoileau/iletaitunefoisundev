<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\SaveRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: SaveRepository::class)]
class Save
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ManyToOne(targetEntity: World::class)]
    private ?World $world = null;

    #[ManyToOne(targetEntity: Continent::class)]
    private ?Continent $continent = null;

    #[ManyToOne(targetEntity: Region::class)]
    private ?Region $region = null;

    #[ManyToOne(targetEntity: Quest::class)]
    private ?Quest $quest = null;

    #[ManyToOne(targetEntity: Checkpoint::class)]
    private ?Checkpoint $checkpoint = null;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $savedAt;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): void
    {
        $this->world = $world;
    }

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): void
    {
        $this->continent = $continent;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): void
    {
        $this->region = $region;
    }

    public function getQuest(): ?Quest
    {
        return $this->quest;
    }

    public function setQuest(?Quest $quest): void
    {
        $this->quest = $quest;
    }

    public function getCheckpoint(): ?Checkpoint
    {
        return $this->checkpoint;
    }

    public function setCheckpoint(?Checkpoint $checkpoint): void
    {
        $this->checkpoint = $checkpoint;
    }

    public function getSavedAt(): DateTimeImmutable
    {
        return $this->savedAt;
    }

    public function setSavedAt(DateTimeImmutable $savedAt): void
    {
        $this->savedAt = $savedAt;
    }
}
