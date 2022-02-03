<?php

declare(strict_types=1);

namespace App\Adventure\Validator;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Gateway\CheckpointGateway;
use App\Adventure\UseCase\SaveCheckpoint\SaveCheckpointInput;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniqueCheckpointValidator extends ConstraintValidator
{
    /**
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     */
    public function __construct(private CheckpointGateway $checkpointGateway)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof SaveCheckpointInput || !$constraint instanceof UniqueCheckpoint) {
            return;
        }

        if ($this->checkpointGateway->hasAlreadySavedCheckpoint($value->journey, $value->quest)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ quest }}', $value->quest->getName())
                ->setParameter('{{ player }}', $value->journey->getPlayer()->getName())
                ->atPath('id')
                ->addViolation();
        }
    }
}
