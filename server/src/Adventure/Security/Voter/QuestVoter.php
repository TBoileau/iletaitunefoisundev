<?php

declare(strict_types=1);

namespace App\Adventure\Security\Voter;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Security\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class QuestVoter extends Voter
{
    /**
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     */
    public function __construct(private CheckpointGateway $checkpointGateway)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Quest && in_array($attribute, ['start', 'finish'], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        /** @var Player $player */
        $player = $user->getPlayer();

        /** @var Quest $quest */
        $quest = $subject;

        return match ($attribute) {
            'start' => !$this->checkpointGateway->hasStartedQuest($player, $quest),
            default => $this->checkpointGateway->hasStartedQuest($player, $quest)
                && !$this->checkpointGateway->hasFinishedQuest($player, $quest)
        };
    }
}
