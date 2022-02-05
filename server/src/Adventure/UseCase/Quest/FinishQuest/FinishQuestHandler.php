<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Quest\FinishQuest;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Gateway\CheckpointGateway;
use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FinishQuestHandler implements MessageHandlerInterface
{
    /**
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     */
    public function __construct(private CheckpointGateway $checkpointGateway)
    {
    }

    public function __invoke(FinishQuestInput $finishQuestInput): void
    {
        /** @var Checkpoint $checkpoint */
        $checkpoint = $this->checkpointGateway->getCheckpointByPlayerAndQuest(
            $finishQuestInput->player,
            $finishQuestInput->quest
        );
        $checkpoint->setFinishedAt(new DateTimeImmutable());
        $this->checkpointGateway->save($checkpoint);
    }
}
