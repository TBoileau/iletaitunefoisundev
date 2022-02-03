<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Adventure\Doctrine\Repository\ContinentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/adventure',
)]
#[Entity(repositoryClass: ContinentRepository::class)]
class Continent implements Stringable
{
    #[Groups('read')]
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private ?int $id = null;

    #[Column(type: Types::STRING)]
    #[Groups('read')]
    private string $name;

    #[ManyToOne(targetEntity: World::class, inversedBy: 'continents')]
    #[JoinColumn(nullable: false)]
    #[Groups('read')]
    #[ApiProperty(readableLink: false)]
    private World $world;

    /**
     * @var Collection<int, Region>
     */
    #[ApiSubresource(maxDepth: 1)]
    #[OneToMany(mappedBy: 'continent', targetEntity: Region::class)]
    private Collection $regions;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getWorld(): World
    {
        return $this->world;
    }

    public function setWorld(World $world): void
    {
        $this->world = $world;
    }

    /**
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
