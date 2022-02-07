<?php

declare(strict_types=1);

namespace App\Security\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class ExistingUserForToken extends Constraint
{
    public string $message = 'This token {{ value }} is not linked to any user.';
}
