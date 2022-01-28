<?php

declare(strict_types=1);

namespace App\Security\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class UniqueEmail extends Constraint
{
    public string $message = 'This email {{ value }} is not unique.';
}
