<?php

declare(strict_types=1);

namespace App\Adventure\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class UniqueCheckpoint extends Constraint
{
    public string $message = 'The quest {{ quest }} for {{ player }} has been already saved.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
