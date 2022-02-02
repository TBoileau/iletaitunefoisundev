<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Adventure\Entity\Player;
use App\Security\Doctrine\Repository\UserRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity(repositoryClass: UserRepository::class)]
class User extends AbstractUser
{
    #[OneToOne(inversedBy: 'user', targetEntity: Player::class, fetch: 'EAGER')]
    private ?Player $player = null;

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }
}
