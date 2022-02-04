<?php

declare(strict_types=1);

namespace App\Content\Entity;

use App\Content\Doctrine\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

#[Entity(repositoryClass: CourseRepository::class)]
class Course extends Node
{
    #[Url]
    #[Regex(pattern: '/^https:\/\/www\.youtube\.com\/watch\?v=(.+)$/')]
    #[Column(type: Types::STRING)]
    #[Groups('read')]
    private string $youtubeUrl = '';

    #[Column(type: Types::TEXT)]
    #[Groups('read')]
    private string $description = '';

    #[Column(type: Types::TEXT)]
    #[Groups('read')]
    private string $content = '';

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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
