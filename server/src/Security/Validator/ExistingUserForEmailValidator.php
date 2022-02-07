<?php

declare(strict_types=1);

namespace App\Security\Validator;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ExistingUserForEmailValidator extends ConstraintValidator
{
    public function __construct(private UserLoaderInterface $userLoader)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || '' === $value || !$constraint instanceof ExistingUserForEmail) {
            return;
        }

        if (null === $this->userLoader->loadUserByIdentifier($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
