<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Quest\StartQuest;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Gateway\CheckpointGateway;
use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class StartQuestHandler implements MessageHandlerInterface
{
    /**
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     */
    public function __construct(private CheckpointGateway $checkpointGateway)
    {
    }

    public function __invoke(StartQuestInput $startQuestInput): void
    {
        $checkpoint = new Checkpoint();
        $checkpoint->setQuest($startQuestInput->quest);
        $checkpoint->setJourney($startQuestInput->player->getJourney());
        $checkpoint->setStartedAt(new DateTimeImmutable());
        $this->checkpointGateway->save($checkpoint);
    }
}
