<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use IncentiveFactory\Domain\Path\Level;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\TrainingRepository;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Type\PathLevelType;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[Id]
    #[Column(type: 'ulid')]
    private Ulid $id;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $publishedAt;

    #[Column(type: Types::STRING)]
    private string $name;

    #[Column(type: Types::STRING)]
    private string $slug;

    #[Column(type: Types::TEXT)]
    private string $description;

    #[Column(type: PathLevelType::NAME)]
    private Level $level;

    #[Column(type: Types::TEXT)]
    private string $prerequisites;

    #[Column(type: Types::TEXT)]
    private string $skills;

    #[Column(type: Types::STRING)]
    private string $image;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): Training
    {
        $this->id = $id;

        return $this;
    }

    public function getPublishedAt(): DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeInterface $publishedAt): Training
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Training
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Training
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Training
    {
        $this->description = $description;

        return $this;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function setLevel(Level $level): Training
    {
        $this->level = $level;

        return $this;
    }

    public function getPrerequisites(): string
    {
        return $this->prerequisites;
    }

    public function setPrerequisites(string $prerequisites): Training
    {
        $this->prerequisites = $prerequisites;

        return $this;
    }

    public function getSkills(): string
    {
        return $this->skills;
    }

    public function setSkills(string $skills): Training
    {
        $this->skills = $skills;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): Training
    {
        $this->image = $image;

        return $this;
    }
}
