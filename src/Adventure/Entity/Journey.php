<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\JourneyRepository;
use App\Security\Entity\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: JourneyRepository::class)]
class Journey
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[OneToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[ManyToOne(targetEntity: Level::class)]
    #[JoinColumn(nullable: false)]
    private Level $currentLevel;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getCurrentLevel(): Level
    {
        return $this->currentLevel;
    }

    public function setCurrentLevel(Level $currentLevel): void
    {
        $this->currentLevel = $currentLevel;
    }
}
