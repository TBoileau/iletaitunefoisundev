<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Adventure\Controller\FinishQuestController;
use App\Adventure\Controller\GetCheckpointController;
use App\Adventure\Controller\StartQuestController;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Doctrine\Type\DifficultyType;
use App\Adventure\Doctrine\Type\QuestTypeType;
use App\Content\Entity\Course;
use App\Content\Entity\Quiz;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Stringable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    itemOperations: [
        'get',
        'checkpoint' => [
            'controller' => GetCheckpointController::class,
            'status' => Response::HTTP_OK,
            'method' => Request::METHOD_GET,
            'output' => Checkpoint::class,
            'path' => '/quests/{id}/checkpoint',
            'security' => 'is_granted("ROLE_PLAYER")',
        ],
        'finish' => [
            'controller' => FinishQuestController::class,
            'status' => Response::HTTP_ACCEPTED,
            'method' => Request::METHOD_POST,
            'output' => Checkpoint::class,
            'path' => '/quests/{id}/finish',
            'security' => 'is_granted("ROLE_PLAYER") and is_granted("finish", object)',
        ],
        'start' => [
            'controller' => StartQuestController::class,
            'status' => Response::HTTP_CREATED,
            'method' => Request::METHOD_POST,
            'output' => Checkpoint::class,
            'path' => '/quests/{id}/start',
            'security' => 'is_granted("ROLE_PLAYER") and is_granted("start", object)',
            'normalization_context' => ['groups' => ['read']],
        ],
    ],
    attributes: ['pagination_enabled' => false],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/adventure',
)]
#[Entity(repositoryClass: QuestRepository::class)]
class Quest implements Stringable
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    #[Groups(['read', 'map'])]
    private ?int $id = null;

    #[Column(type: Types::STRING)]
    #[Groups(['read', 'map'])]
    private string $name;

    #[ManyToOne(targetEntity: Region::class, inversedBy: 'quests')]
    #[JoinColumn(nullable: false)]
    #[Groups('read')]
    #[ApiProperty(readableLink: false)]
    private Region $region;

    #[Column(type: DifficultyType::NAME, length: 1)]
    private Difficulty $difficulty;

    #[Column(name: 'quest_type', type: QuestTypeType::NAME, length: 1)]
    private Type $type;

    #[ManyToOne(targetEntity: Course::class)]
    #[JoinColumn(nullable: false)]
    #[Groups(['read', 'map'])]
    private Course $course;

    #[ManyToOne(targetEntity: Quiz::class)]
    #[Groups(['read', 'map'])]
    #[ApiProperty(readableLink: false)]
    private ?Quiz $quiz = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }

    public function getDifficulty(): Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(Difficulty $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    #[Groups(['read', 'map'])]
    public function getDifficultyName(): string
    {
        return $this->difficulty->name;
    }

    #[Groups(['read', 'map'])]
    public function getTypeName(): string
    {
        return $this->type->name;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): void
    {
        $this->course = $course;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): void
    {
        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }
}
