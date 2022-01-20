<?php

declare(strict_types=1);

namespace App\Domain\Course\Entity;

use App\Domain\Node\Entity\Node;
use App\Infrastructure\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: CourseRepository::class)]
class Course extends Node
{
    #[Column(type: Types::STRING, unique: true)]
    private string $youtubeId;

    #[Column(type: Types::TEXT)]
    private string $description;

    public function getYoutubeId(): string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(string $youtubeId): void
    {
        $this->youtubeId = $youtubeId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
