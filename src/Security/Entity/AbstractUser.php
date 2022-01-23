<?php

declare(strict_types=1);

namespace App\Security\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

abstract class AbstractUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    protected Ulid $id;

    #[Column(type: Types::STRING, unique: true)]
    protected string $email;

    #[Column(type: Types::STRING, nullable: true)]
    protected ?string $password;

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
}
