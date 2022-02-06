<?php

declare(strict_types=1);

namespace App\Security\UseCase\RequestForgottenPassword;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class RequestForgottenPasswordInput
{
    #[NotBlank]
    #[Email]
    public string $email = '';
}
