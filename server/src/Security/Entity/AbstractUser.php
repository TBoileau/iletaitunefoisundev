<?php

declare(strict_types=1);

namespace App\Security\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use OpenApi\Attributes as OpenApi;
use Stringable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

abstract class AbstractUser implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    #[OpenApi\Property(type: 'string')]
    protected Ulid $id;

    #[Column(type: Types::STRING, unique: true)]
    protected string $email = '';

    #[Column(type: Types::STRING, nullable: true)]
    protected ?string $password = '';

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
