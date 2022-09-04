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
use IncentiveFactory\Domain\Path\Level;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\CourseRepository;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Type\PathLevelType;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[Id]
    #[Column(type: 'ulid')]
    private Ulid $id;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $publishedAt;

    #[Column(type: Types::STRING)]
    private string $name;

    #[Column(type: Types::TEXT)]
    private string $excerpt;

    #[Column(type: Types::TEXT)]
    private string $content;

    #[Column(type: Types::STRING)]
    private string $slug;

    #[Column(type: Types::STRING)]
    private string $image;

    #[Column(type: Types::STRING)]
    private string $video;

    /**
     * @var array<array-key, string>
     */
    #[Column(type: Types::JSON)]
    private array $thread;

    #[Column(type: PathLevelType::NAME)]
    private Level $level;

    #[ManyToOne(targetEntity: Training::class)]
    #[JoinColumn(nullable: false)]
    private Training $training;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): Course
    {
        $this->id = $id;

        return $this;
    }

    public function getPublishedAt(): DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeInterface $publishedAt): Course
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Course
    {
        $this->name = $name;

        return $this;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): Course
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Course
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Course
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): Course
    {
        $this->image = $image;

        return $this;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function setVideo(string $video): Course
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return array<array-key, string>
     */
    public function getThread(): array
    {
        return $this->thread;
    }

    /**
     * @param array<array-key, string> $thread
     */
    public function setThread(array $thread): Course
    {
        $this->thread = $thread;

        return $this;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function setLevel(Level $level): Course
    {
        $this->level = $level;

        return $this;
    }

    public function getTraining(): Training
    {
        return $this->training;
    }

    public function setTraining(Training $training): Course
    {
        $this->training = $training;

        return $this;
    }
}
