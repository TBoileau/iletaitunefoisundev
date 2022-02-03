<?php

declare(strict_types=1);

namespace App\Security\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Stringable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractUser implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
{
    #[Groups('read')]
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    protected ?int $id = null;

    #[Column(type: Types::STRING, unique: true)]
    #[Groups('read')]
    protected string $email = '';

    #[Column(type: Types::STRING, nullable: true)]
    protected ?string $password = '';

    public function getId(): ?int
    {
        return $this->id;
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
