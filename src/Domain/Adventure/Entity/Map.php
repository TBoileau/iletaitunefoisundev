<?php

declare(strict_types=1);

namespace App\Domain\Adventure\Entity;

use App\Infrastructure\Repository\MapRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: MapRepository::class)]
class Map
{
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[OneToOne(targetEntity: Level::class)]
    #[JoinColumn(nullable: false)]
    private Level $start;

    #[OneToOne(mappedBy: 'next', targetEntity: Map::class)]
    private ?Map $previous = null;

    #[OneToOne(inversedBy: 'previous', targetEntity: Map::class)]
    private ?Map $next = null;

    #[Column(type: Types::STRING)]
    private string $name;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getStart(): Level
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
