<?php

declare(strict_types=1);

namespace App\Content\Entity\Quiz;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Content\Controller\SubmitResponseController;
use App\Content\Doctrine\Repository\ResponseRepository;
use App\Content\Entity\Answer;
use App\Content\Entity\Question;
use App\Content\UseCase\SubmitResponse\SubmitResponseInput;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get',
        'put' => [
            'messenger' => 'input',
            'controller' => SubmitResponseController::class,
            'output' => Session::class,
            'input' => SubmitResponseInput::class,
        ],
    ],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/content',
)]
#[Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private ?int $id = null;

    #[ManyToOne(targetEntity: Session::class, inversedBy: 'responses')]
    #[JoinColumn(nullable: false)]
    private Session $session;

    #[ManyToOne(targetEntity: Question::class)]
    #[JoinColumn(nullable: false)]
    #[Groups('read')]
    private Question $question;

    /**
     * @var Collection<int, Answer>
     */
    #[ManyToMany(targetEntity: Answer::class)]
    #[JoinTable(name: 'response_answers')]
    #[Groups(['read', 'write'])]
    #[ApiProperty(writableLink: true)]
    private Collection $answers;

    #[Column(type: Types::BOOLEAN)]
    #[Groups('read')]
    private bool $valid = false;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups('read')]
    private ?DateTimeImmutable $respondedAt = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    public function getRespondedAt(): ?DateTimeImmutable
    {
        return $this->respondedAt;
    }

    public function setRespondedAt(?DateTimeImmutable $respondedAt): void
    {
        $this->respondedAt = $respondedAt;
    }
}
