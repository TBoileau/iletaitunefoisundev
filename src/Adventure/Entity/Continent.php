<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\ContinentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: ContinentRepository::class)]
class Continent
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[Column(type: Types::STRING)]
    private string $name;

    #[ManyToOne(targetEntity: World::class, inversedBy: 'continents')]
    #[JoinColumn(nullable: false)]
    private World $world;

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

    public function getWorld(): World
    {
        return $this->world;
    }

    public function setWorld(World $world): void
    {
        $this->world = $world;
    }
}
