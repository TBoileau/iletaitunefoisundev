<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Core\Bus\Command\CommandInterface;
use App\Security\Validator\UniqueEmail;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class Register implements CommandInterface
{
    #[NotBlank]
    #[Email]
    #[UniqueEmail]
    private string $email = '';

    #[Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/')]
    #[NotBlank]
    private string $plainPassword = '';

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
