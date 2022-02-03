<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Adventure\Controller\FinishQuestController;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Doctrine\Type\DifficultyType;
use App\Adventure\Doctrine\Type\QuestTypeType;
use App\Content\Entity\Course;
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
use Stringable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get',
        'finish' => [
            'controller' => FinishQuestController::class,
            'status' => Response::HTTP_NO_CONTENT,
            'method' => Request::METHOD_POST,
            'path' => '/quests/{id}/finish',
        ],
    ],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/adventure',
)]
#[Entity(repositoryClass: QuestRepository::class)]
class Quest implements Stringable
{
    #[Groups('read')]
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private ?int $id = null;

    #[Column(type: Types::STRING)]
    #[Groups('read')]
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

    /**
     * @var Collection<int, Quest>
     */
    #[ApiSubresource(maxDepth: 1)]
    #[ManyToMany(targetEntity: Quest::class)]
    #[JoinTable(name: 'quest_relatives')]
    private Collection $relatives;

    #[ManyToOne(targetEntity: Course::class)]
    #[JoinColumn(nullable: false)]
    #[Groups('read')]
    private Course $course;

    public function __construct()
    {
        $this->relatives = new ArrayCollection();
    }

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

    #[Groups('read')]
    public function getDifficultyName(): string
    {
        return $this->difficulty->name;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getRelatives(): Collection
    {
        return $this->relatives;
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
}
