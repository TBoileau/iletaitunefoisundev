<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\MapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;
use Stringable;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: MapRepository::class)]
class Map implements Stringable
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

    /**
     * @var Collection<int, Level>
     */
    #[OneToMany(mappedBy: 'map', targetEntity: Level::class)]
    #[OrderBy(['order' => 'ASC'])]
    private Collection $levels;

    public function __construct()
    {
        $this->levels = new ArrayCollection();
    }

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }
}
