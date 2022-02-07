<?php

declare(strict_types=1);

namespace App\Security\Validator;

use App\Security\Entity\User;
use App\Security\UseCase\ResetForgottenPassword\ResetForgottenPasswordInput;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ForgottenPasswordTokenCorrectForUserValidator extends ConstraintValidator
{
    public function __construct(private UserLoaderInterface $userLoader)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_object($value) || !$value instanceof ResetForgottenPasswordInput || !$constraint instanceof ForgottenPasswordTokenCorrectForUser) {
            return;
        }

        /** @var User $user */
        $user = $this->userLoader->loadUserByIdentifier($value->email);
        if ($user->getForgottenPasswordToken() !== $value->forgottenPasswordToken) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
