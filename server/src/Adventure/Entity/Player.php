<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\PlayerRepository;
use App\Security\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Stringable;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: PlayerRepository::class)]
class Player implements Stringable
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[Column(type: Types::STRING)]
    private string $name;

    #[OneToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[OneToOne(inversedBy: 'player', targetEntity: Journey::class, cascade: ['persist'])]
    #[JoinColumn(nullable: false)]
    private Journey $journey;

    #[OneToOne(mappedBy: 'player', targetEntity: Save::class, cascade: ['persist'])]
    private ?Save $save = null;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getJourney(): Journey
    {
        return $this->journey;
    }

    public function setJourney(Journey $journey): void
    {
        $this->journey = $journey;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getSave(): ?Save
    {
        return $this->save;
    }

    public function setSave(?Save $save): void
    {
        $this->save = $save;
    }
}
