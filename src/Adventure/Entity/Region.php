<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\RegionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[Column(type: Types::STRING)]
    private string $name;

    #[ManyToOne(targetEntity: Continent::class, inversedBy: 'regions')]
    #[JoinColumn(nullable: false)]
    private Continent $continent;

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
}
