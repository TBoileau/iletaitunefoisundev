<?php

declare(strict_types=1);

namespace App\Security\UseCase\ResetForgottenPassword;

use App\Security\Entity\User;
use App\Security\Validator\ExistingUserForToken;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class ResetForgottenPasswordInput
{
    #[ExistingUserForToken]
    #[NotBlank]
    public string $forgottenPasswordToken = '';

    #[Regex(pattern: User::PASSWORD_PATTERN)]
    #[NotBlank]
    public string $plainPassword = '';
}
