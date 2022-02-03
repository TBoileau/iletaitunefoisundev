<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\SaveCheckpoint;

use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Quest;
use App\Adventure\Validator\UniqueCheckpoint;
use Symfony\Component\Validator\Constraints\NotNull;

#[UniqueCheckpoint]
final class SaveCheckpointInput
{
    #[NotNull]
    public Journey $journey;

    #[NotNull]
    public Quest $quest;
}
