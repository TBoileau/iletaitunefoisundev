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
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\CourseLogRepository;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: CourseLogRepository::class)]
class CourseLog
{
    #[Id]
    #[Column(type: 'ulid')]
    private Ulid $id;

    #[ManyToOne(targetEntity: Path::class)]
    #[JoinColumn(nullable: false)]
    private Path $path;

    #[ManyToOne(targetEntity: Course::class)]
    #[JoinColumn(nullable: false)]
    private Course $course;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $beganAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeInterface $completedAt = null;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): CourseLog
    {
        $this->id = $id;

        return $this;
    }

    public function getPath(): Path
    {
        return $this->path;
    }

    public function setPath(Path $path): CourseLog
    {
        $this->path = $path;

        return $this;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): CourseLog
    {
        $this->course = $course;

        return $this;
    }

    public function getCompletedAt(): ?DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?DateTimeInterface $completedAt): CourseLog
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getBeganAt(): DateTimeInterface
    {
        return $this->beganAt;
    }

    public function setBeganAt(DateTimeInterface $beganAt): CourseLog
    {
        $this->beganAt = $beganAt;

        return $this;
    }
}
