<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Checkpoint\PassCheckpoint;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Adventure\Gateway\QuestGateway;
use App\Core\Bus\Command\CommandHandlerInterface;
use App\Core\Bus\Event\EventBusInterface;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class PassCheckpointHandler implements CommandHandlerInterface
{
    /**
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     * @param QuestGateway<Quest>           $questGateway
     */
    public function __construct(
        private UlidGeneratorInterface $ulidGenerator,
        private CheckpointGateway $checkpointGateway,
        private QuestGateway $questGateway,
        private TokenStorageInterface $tokenStorage,
        private EventBusInterface $eventBus
    ) {
    }

    public function __invoke(PassCheckpoint $passCheckpoint): void
    {
        $quest = $this->questGateway->getQuestById($passCheckpoint->getQuest());

        /** @var TokenInterface $token */
        $token = $this->tokenStorage->getToken();

        /** @var User $user */
        $user = $token->getUser();

        /** @var Player $player */
        $player = $user->getPlayer();

        $checkpoint = new Checkpoint();
        $checkpoint->setId($this->ulidGenerator->generate());
        $checkpoint->setQuest($quest);
        $checkpoint->setPassedAt(new DateTimeImmutable());
        $checkpoint->setJourney($player->getJourney());
        $this->checkpointGateway->save($checkpoint);
        $this->eventBus->publish(new CheckpointPassed($checkpoint));
    }
}
