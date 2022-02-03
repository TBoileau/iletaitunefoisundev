<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\SaveCheckpoint;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Gateway\CheckpointGateway;
use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SaveCheckpointHandler implements MessageHandlerInterface
{
    /**
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     */
    public function __construct(private CheckpointGateway $checkpointGateway)
    {
    }

    public function __invoke(SaveCheckpointInput $saveCheckpoint): void
    {
        $checkpoint = new Checkpoint();
        $checkpoint->setQuest($saveCheckpoint->quest);
        $checkpoint->setJourney($saveCheckpoint->journey);
        $checkpoint->setPassedAt(new DateTimeImmutable());
        $this->checkpointGateway->save($checkpoint);
    }
}
