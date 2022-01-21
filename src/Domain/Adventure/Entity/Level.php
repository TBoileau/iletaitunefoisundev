<?php

declare(strict_types=1);

namespace App\Domain\Adventure\Entity;

use App\Domain\Node\Entity\Course;
use App\Infrastructure\Repository\LevelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: LevelRepository::class)]
#[UniqueConstraint(columns: ['odr', 'map_id'])]
class Level
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[Column(name: 'odr', type: Types::INTEGER)]
    private int $order;

    #[ManyToOne(targetEntity: Map::class)]
    #[JoinColumn(nullable: false)]
    private Map $map;

    #[OneToOne(inversedBy: 'next', targetEntity: Level::class)]
    private ?Level $previous = null;

    #[OneToOne(mappedBy: 'previous', targetEntity: Level::class)]
    private ?Level $next = null;

    #[OneToOne(targetEntity: Course::class)]
    #[JoinColumn(nullable: false)]
    private Course $course;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    public function setMap(Map $map): void
    {
        $this->map = $map;
    }

    public function getPrevious(): ?Level
    {
        return $this->previous;
    }

    public function setPrevious(?Level $previous): void
    {
        $this->previous = $previous;
    }

    public function getNext(): ?Level
    {
        return $this->next;
    }

    public function setNext(?Level $next): void
    {
        $this->next = $next;
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
