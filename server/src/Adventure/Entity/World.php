<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Doctrine\Repository\WorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: WorldRepository::class)]
class World implements Stringable
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    #[Groups(['get'])]
    private Ulid $id;

    #[Column(type: Types::STRING)]
    #[Groups(['get'])]
    private string $name;

    /**
     * @var Collection<int, Continent>
     */
    #[OneToMany(mappedBy: 'world', targetEntity: Continent::class)]
    private Collection $continents;

    public function __construct()
    {
        $this->continents = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<int, Continent>
     */
    public function getContinents(): Collection
    {
        return $this->continents;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
