<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Node\Entity\History;
use App\Security\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: UserRepository::class)]
class User extends AbstractUser
{
    /**
     * @var Collection<int, History>
     */
    #[OneToMany(mappedBy: 'user', targetEntity: History::class)]
    private Collection $history;

    public function __construct()
    {
        $this->history = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }
}
