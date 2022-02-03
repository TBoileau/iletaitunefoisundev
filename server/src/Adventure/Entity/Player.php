<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Doctrine\Repository\PlayerRepository;
use App\Security\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Stringable;

#[Entity(repositoryClass: PlayerRepository::class)]
class Player implements Stringable
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private ?int $id = null;

    #[Column(type: Types::STRING)]
    private string $name = '';

    #[OneToOne(mappedBy: 'player', targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[OneToOne(inversedBy: 'player', targetEntity: Journey::class, cascade: ['persist'], fetch: 'EAGER')]
    #[JoinColumn(nullable: false)]
    private Journey $journey;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->user->setPlayer($this);
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
}
