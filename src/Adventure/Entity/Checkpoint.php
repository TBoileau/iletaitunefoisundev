<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\CheckpointRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Stringable;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: CheckpointRepository::class)]
class Checkpoint implements Stringable
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ManyToOne(targetEntity: Journey::class, inversedBy: 'checkpoints')]
    #[JoinColumn(nullable: false)]
    private Journey $journey;

    #[ManyToOne(targetEntity: Level::class)]
    #[JoinColumn(nullable: false)]
    private Level $level;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $passedAt;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getJourney(): Journey
    {
        return $this->journey;
    }

    public function setJourney(Journey $journey): void
    {
        $this->journey = $journey;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function setLevel(Level $level): void
    {
        $this->level = $level;
    }

    public function getPassedAt(): DateTimeImmutable
    {
        return $this->passedAt;
    }

    public function setPassedAt(DateTimeImmutable $passedAt): void
    {
        $this->passedAt = $passedAt;
    }

    public function __toString(): string
    {
        return sprintf('%s - %s - %s', $this->journey, $this->level->getMap(), $this->level);
    }
}
