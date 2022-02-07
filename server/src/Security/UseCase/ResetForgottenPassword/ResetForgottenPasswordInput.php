<?php

declare(strict_types=1);

namespace App\Security\UseCase\ResetForgottenPassword;

use App\Security\Entity\User;
use App\Security\Validator\ExistingUserForEmail;
use App\Security\Validator\ForgottenPasswordTokenCorrectForUser;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[GroupSequence(['ResetForgottenPasswordInput', 'Last'])]
#[ForgottenPasswordTokenCorrectForUser(groups: ['Last'])]
final class ResetForgottenPasswordInput
{
    #[NotBlank]
    #[Email]
    #[ExistingUserForEmail]
    public string $email = '';

    #[NotBlank]
    public string $forgottenPasswordToken = '';

    #[Regex(pattern: User::PASSWORD_PATTERN)]
    #[NotBlank]
    public string $plainPassword = '';
}
