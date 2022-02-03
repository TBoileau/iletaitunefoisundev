<?php

declare(strict_types=1);

namespace App\Security\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Adventure\Entity\Player;
use App\Security\Doctrine\Repository\UserRepository;
use App\Security\UseCase\Register\RegisterInput;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[ApiResource(
    collectionOperations: [
        'register' => [
            'messenger' => 'input',
            'input' => RegisterInput::class,
            'status' => Response::HTTP_CREATED,
            'method' => Request::METHOD_POST,
            'path' => '/register',
        ],
    ],
    itemOperations: [
        'get',
    ],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/security'
)]
#[Entity(repositoryClass: UserRepository::class)]
class User extends AbstractUser
{
    #[OneToOne(inversedBy: 'user', targetEntity: Player::class, fetch: 'EAGER')]
    private ?Player $player = null;

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }

    public function getRoles(): array
    {
        return array_merge(['ROLE_USER'], null === $this->player ? [] : ['ROLE_PLAYER']);
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }
}
