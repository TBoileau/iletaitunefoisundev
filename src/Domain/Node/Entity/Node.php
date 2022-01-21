<?php

declare(strict_types=1);

namespace App\Domain\Node\Entity;

use App\Infrastructure\Repository\NodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
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

    /**
     * @var Collection<int, Node>
     */
    #[ManyToMany(targetEntity: Node::class)]
    #[JoinTable(name: 'node_siblings')]
    private Collection $siblings;

    public function __construct()
    {
        $this->siblings = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Node>
     */
    public function getSiblings(): Collection
    {
        return $this->siblings;
    }

    public function addSibling(Node $sibling): void
    {
        if (!$this->siblings->contains($sibling)) {
            $this->siblings->add($sibling);
            $sibling->addSibling($this);
        }
    }

    public function removeSibling(Node $sibling): void
    {
        if ($this->siblings->contains($sibling)) {
            $this->siblings->removeElement($sibling);
            $sibling->removeSibling($this);
        }
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
