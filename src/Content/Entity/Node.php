<?php

declare(strict_types=1);

namespace App\Content\Entity;

use App\Content\Repository\NodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Stringable;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: NodeRepository::class)]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: Types::STRING)]
#[DiscriminatorMap([
    'course' => Course::class,
])]
abstract class Node implements Stringable
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    protected Ulid $id;

    #[Column(type: Types::STRING)]
    protected string $title;

    #[Column(type: Types::STRING, unique: true)]
    protected string $slug;

    abstract public function getType(): string;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
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
