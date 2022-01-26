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
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[Column(type: Types::STRING)]
    private string $name;

    #[OneToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

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
}
