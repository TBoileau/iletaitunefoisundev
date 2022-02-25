<?php

declare(strict_types=1);

namespace App\Adventure\Controller;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Security\Entity\User;
use Symfony\Component\Security\Core\Security;

final class GetCheckpointController
{
    /**
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     */
    public function __construct(
        private CheckpointGateway $checkpointGateway,
        private Security $security
    ) {
    }

    public function __invoke(Quest $quest): ?Checkpoint
    {
        /** @var User $user */
        $user = $this->security->getUser();
        
        /** @var Player $player */
        $player = $user->getPlayer();
        
        return $this->checkpointGateway->getCheckpointByPlayerAndQuest($player, $quest);
    }
}
