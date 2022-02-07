<?php

declare(strict_types=1);

namespace App\Security\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class ForgottenPasswordTokenCorrectForUser extends Constraint
{
    public string $message = 'The token is not correct.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
