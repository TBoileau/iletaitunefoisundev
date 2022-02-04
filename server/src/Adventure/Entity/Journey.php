<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Adventure\Doctrine\Repository\JourneyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get'],
    attributes: ['pagination_enabled' => false],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/adventure',
)]
#[Entity(repositoryClass: JourneyRepository::class)]
class Journey implements Stringable
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    #[Groups('read')]
    private ?int $id = null;

    #[OneToOne(mappedBy: 'journey', targetEntity: Player::class)]
    private Player $player; /* @phpstan-ignore-line */

    /**
     * @var Collection<int, Checkpoint>
     */
    #[OneToMany(mappedBy: 'journey', targetEntity: Checkpoint::class)]
    #[OrderBy(['passedAt' => 'DESC'])]
    #[ApiSubresource(maxDepth: 1)]
    private Collection $checkpoints;

    public function __construct()
    {
        $this->checkpoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString(): string
    {
        return sprintf('Journal de bord de %s', $this->player);
    }
}
