<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Adventure\Doctrine\Repository\CheckpointRepository;
use App\Content\Entity\Quiz\Session;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/adventure'
)]
#[Entity(repositoryClass: CheckpointRepository::class)]
class Checkpoint implements Stringable
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    #[Groups('read')]
    private ?int $id = null;

    #[ManyToOne(targetEntity: Journey::class, inversedBy: 'checkpoints')]
    #[JoinColumn(nullable: false)]
    private Journey $journey;

    #[ManyToOne(targetEntity: Quest::class)]
    #[JoinColumn(nullable: false)]
    #[Groups('read')]
    #[ApiProperty(readableLink: false)]
    private Quest $quest;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups('read')]
    private DateTimeImmutable $startedAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups('read')]
    private ?DateTimeImmutable $finishedAt = null;

    #[OneToOne(targetEntity: Session::class)]
    #[Groups('read')]
    #[ApiProperty(readableLink: false)]
    private ?Session $session;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourney(): Journey
    {
        return $this->journey;
    }

    public function setJourney(Journey $journey): void
    {
        $this->journey = $journey;
    }

    public function getQuest(): Quest
    {
        return $this->quest;
    }

    public function setQuest(Quest $quest): void
    {
        $this->quest = $quest;
    }

    public function getFinishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?DateTimeImmutable $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

    public function getStartedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function __toString(): string
    {
        if (null !== $this->finishedAt) {
            return sprintf('%s terminée le %s', $this->quest, $this->finishedAt->format('d/m/Y H:i:s'));
        }

        return sprintf('%s commencée le %s', $this->quest, $this->startedAt->format('d/m/Y H:i:s'));
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): void
    {
        $this->session = $session;
    }
}
