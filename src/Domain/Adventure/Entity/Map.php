<?php

declare(strict_types=1);

namespace App\Domain\Adventure\Entity;

use App\Infrastructure\Repository\MapRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: MapRepository::class)]
class Map
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[OneToOne(targetEntity: Level::class)]
    private ?Level $start = null;

    #[OneToOne(inversedBy: 'next', targetEntity: Map::class)]
    private ?Map $previous = null;

    #[OneToOne(mappedBy: 'previous', targetEntity: Map::class)]
    private ?Map $next = null;

    #[Column(type: Types::STRING)]
    private string $name;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getStart(): ?Level
    {
        return $this->start;
    }

    public function setStart(Level $start): void
    {
        $this->start = $start;
    }

    public function getPrevious(): ?Map
    {
        return $this->previous;
    }

    public function setPrevious(?Map $previous): void
    {
        $this->previous = $previous;
    }

    public function getNext(): ?Map
    {
        return $this->next;
    }

    public function setNext(?Map $next): void
    {
        $this->next = $next;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
