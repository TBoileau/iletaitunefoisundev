<?php

declare(strict_types=1);

namespace App\Content\Entity;

use App\Content\Doctrine\Repository\NodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: NodeRepository::class)]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: Types::STRING)]
#[DiscriminatorMap([
    'course' => Course::class,
    'quiz' => Quiz::class,
])]
abstract class Node implements Stringable
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    protected ?int $id = null;

    #[Column(type: Types::STRING)]
    #[Groups('read')]
    protected string $title = '';

    #[Column(type: Types::STRING, unique: true)]
    protected string $slug = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
