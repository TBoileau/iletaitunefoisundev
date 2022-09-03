<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\PlayerRepository;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Type\PlayerGenderType;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[Id]
    #[Column(type: 'ulid')]
    private Ulid $id;

    #[Column(type: PlayerGenderType::NAME)]
    private Gender $gender;

    #[Column(type: Types::STRING)]
    private string $email;

    #[Column(type: Types::STRING)]
    private string $nickname;

    #[Column(type: Types::STRING)]
    private string $password;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $avatar;

    #[Column(type: 'uuid', nullable: true)]
    private ?Uuid $registrationToken = null;

    #[Column(type: 'uuid', nullable: true)]
    private ?Uuid $forgottenPasswordToken = null;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeInterface $forgottenPasswordExpiredAt = null;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeInterface $registeredAt = null;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): Player
    {
        $this->id = $id;

        return $this;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): Player
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Player
    {
        $this->email = $email;

        return $this;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): Player
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): Player
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): Player
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRegistrationToken(): ?Uuid
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?Uuid $registrationToken): Player
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    public function getForgottenPasswordToken(): ?Uuid
    {
        return $this->forgottenPasswordToken;
    }

    public function setForgottenPasswordToken(?Uuid $forgottenPasswordToken): Player
    {
        $this->forgottenPasswordToken = $forgottenPasswordToken;

        return $this;
    }

    public function getForgottenPasswordExpiredAt(): ?DateTimeInterface
    {
        return $this->forgottenPasswordExpiredAt;
    }

    public function setForgottenPasswordExpiredAt(?DateTimeInterface $forgottenPasswordExpiredAt): Player
    {
        $this->forgottenPasswordExpiredAt = $forgottenPasswordExpiredAt;

        return $this;
    }

    public function getRegisteredAt(): ?DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(?DateTimeInterface $registeredAt): Player
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }
}
