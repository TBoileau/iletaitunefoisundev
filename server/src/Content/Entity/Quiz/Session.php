<?php

declare(strict_types=1);

namespace App\Content\Entity\Quiz;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Adventure\Entity\Player;
use App\Content\Doctrine\Repository\SessionRepository;
use App\Content\Entity\Quiz;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/content/quiz/',
)]
#[Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    #[Groups('read')]
    private ?int $id = null;

    #[ManyToOne(targetEntity: Quiz::class)]
    #[JoinColumn(nullable: false)]
    #[Groups('read')]
    #[ApiProperty(readableLink: false)]
    private Quiz $quiz;

    #[ManyToOne(targetEntity: Player::class)]
    #[JoinColumn(nullable: false)]
    private Player $player;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups('read')]
    private DateTimeImmutable $startedAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups('read')]
    private ?DateTimeImmutable $finishedAt = null;

    /**
     * @var Collection<int, Response>
     */
    #[OneToMany(mappedBy: 'session', targetEntity: Response::class, cascade: ['persist'])]
    #[Groups('read')]
    #[ApiProperty(readableLink: false)]
    private Collection $responses;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    /**
     * @return Collection<int, Response>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    /**
     * @param Collection<int, Response> $responses
     */
    public function setResponses(Collection $responses): void
    {
        $this->responses = $responses;
    }

    public function getStartedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function getFinishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?DateTimeImmutable $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

    public function isFinished(): bool
    {
        return $this->responses->count() === $this->responses
                ->filter(static fn (Response $response): bool => null !== $response->getRespondedAt())
                ->count();
    }
}
