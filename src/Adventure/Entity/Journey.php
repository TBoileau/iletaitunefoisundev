<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\JourneyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: JourneyRepository::class)]
class Journey
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[OneToOne(mappedBy: 'journey', targetEntity: Player::class)]
    private Player $player; /* @phpstan-ignore-line */

    /**
     * @var Collection<int, Checkpoint>
     */
    #[OneToMany(mappedBy: 'journey', targetEntity: Checkpoint::class)]
    #[OrderBy(['passedAt' => 'DESC'])]
    private Collection $checkpoints;

    #[OneToOne(targetEntity: Save::class, cascade: ['persist'])]
    private ?Save $save = null;

    public function __construct()
    {
        $this->checkpoints = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Checkpoint>
     */
    public function getCheckpoints(): Collection
    {
        return $this->checkpoints;
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
