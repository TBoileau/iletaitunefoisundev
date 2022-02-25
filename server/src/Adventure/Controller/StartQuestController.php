<?php

declare(strict_types=1);

namespace App\Adventure\Controller;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Adventure\UseCase\Quest\StartQuest\StartQuestInput;
use App\Security\Entity\User;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

final class StartQuestController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus, private Security $security)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Quest $quest): Checkpoint
    {
        /** @var User $user */
        $user = $this->security->getUser();

        /** @var Player $player */
        $player = $user->getPlayer();

        /** @var Checkpoint $checkpoint */
        $checkpoint = $this->handle(StartQuestInput::create($player, $quest));

        return $checkpoint;
    }
}
