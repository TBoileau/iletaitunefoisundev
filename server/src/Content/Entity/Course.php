<?php

declare(strict_types=1);

namespace App\Content\Entity;

use App\Content\Doctrine\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

#[Entity(repositoryClass: CourseRepository::class)]
class Course extends Node
{
    #[Url]
    #[Regex(pattern: '/^https:\/\/www\.youtube\.com\/watch\?v=(.+)$/')]
    #[Column(type: Types::STRING)]
    private string $youtubeUrl = '';

    #[Column(type: Types::TEXT)]
    private string $description = '';

    public function getYoutubeUrl(): string
    {
        return $this->youtubeUrl;
    }

    public function setYoutubeUrl(string $youtubeUrl): void
    {
        $this->youtubeUrl = $youtubeUrl;
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
