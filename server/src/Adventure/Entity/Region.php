<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Doctrine\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: RegionRepository::class)]
class Region implements Stringable
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    #[Groups(['get'])]
    private Ulid $id;

    #[Column(type: Types::STRING)]
    #[Groups(['get'])]
    private string $name;

    #[ManyToOne(targetEntity: Continent::class, inversedBy: 'regions')]
    #[JoinColumn(nullable: false)]
    private Continent $continent;

    /**
     * @var Collection<int, Quest>
     */
    #[OneToMany(mappedBy: 'region', targetEntity: Quest::class)]
    private Collection $quests;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
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

    public function getContinent(): Continent
    {
        return $this->continent;
    }

    public function setContinent(Continent $continent): void
    {
        $this->continent = $continent;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
