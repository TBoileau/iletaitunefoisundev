<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Security\Entity\User;
use App\Security\Validator\UniqueEmail;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class RegisterInput
{
    #[NotBlank]
    #[Email]
    #[UniqueEmail]
    public string $email = '';

    #[Regex(pattern: User::PASSWORD_PATTERN)]
    #[NotBlank]
    public string $plainPassword = '';
}
