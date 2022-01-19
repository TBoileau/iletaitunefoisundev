<?php

declare(strict_types=1);

namespace App\Domain\Security\Validator;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniqueEmailValidator extends ConstraintValidator
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || '' === $value || !$constraint instanceof UniqueEmail) {
            return;
        }
        if (!$this->userGateway->isUniqueEmail($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
