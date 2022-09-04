<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\PathRepository;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: PathRepository::class)]
class Path
{
    #[Id]
    #[Column(type: 'ulid')]
    private Ulid $id;

    #[ManyToOne(targetEntity: Player::class)]
    #[JoinColumn(nullable: false)]
    private Player $player;

    #[ManyToOne(targetEntity: Training::class)]
    #[JoinColumn(nullable: false)]
    private Training $training;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $beganAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeInterface $completedAt = null;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): Path
    {
        $this->id = $id;

        return $this;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): Path
    {
        $this->player = $player;

        return $this;
    }

    public function getTraining(): Training
    {
        return $this->training;
    }

    public function setTraining(Training $training): Path
    {
        $this->training = $training;

        return $this;
    }

    public function getBeganAt(): DateTimeInterface
    {
        return $this->beganAt;
    }

    public function setBeganAt(DateTimeInterface $beganAt): Path
    {
        $this->beganAt = $beganAt;

        return $this;
    }

    public function getCompletedAt(): ?DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?DateTimeInterface $completedAt): Path
    {
        $this->completedAt = $completedAt;

        return $this;
    }
}
