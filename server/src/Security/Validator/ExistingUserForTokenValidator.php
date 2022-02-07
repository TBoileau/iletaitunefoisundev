<?php

declare(strict_types=1);

namespace App\Security\Validator;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ExistingUserForTokenValidator extends ConstraintValidator
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || '' === $value || !$constraint instanceof ExistingUserForToken) {
            return;
        }

        if (null === $this->userGateway->getUserByForgottenPasswordToken($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
