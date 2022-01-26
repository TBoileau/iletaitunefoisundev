<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\JourneyRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: JourneyRepository::class)]
class Journey
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[OneToOne(mappedBy: 'journey', targetEntity: Player::class)]
    private Player $player; /* @phpstan-ignore-line */

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }
}
