<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Doctrine\Type\DifficultyType;
use App\Adventure\Repository\QuestRepository;
use App\Content\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: QuestRepository::class)]
class Quest
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[Column(type: Types::STRING)]
    private string $name;

    #[ManyToOne(targetEntity: Region::class, inversedBy: 'quests')]
    #[JoinColumn(nullable: false)]
    private Region $region;

    #[Column(type: DifficultyType::NAME, length: 1)]
    private Difficulty $difficulty;

    /**
     * @var Collection<int, Quest>
     */
    #[ManyToMany(targetEntity: Quest::class)]
    #[JoinTable(name: 'quest_relatives')]
    private Collection $relatives;

    #[ManyToOne(targetEntity: Course::class)]
    #[JoinColumn(nullable: false)]
    private Course $course;

    public function __construct()
    {
        $this->relatives = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
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
}
