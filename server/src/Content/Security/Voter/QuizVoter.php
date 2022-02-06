<?php

declare(strict_types=1);

namespace App\Content\Security\Voter;

use App\Adventure\Entity\Player;
use App\Content\Entity\Quiz;
use App\Content\Gateway\SessionGateway;
use App\Security\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class QuizVoter extends Voter
{
    /**
     * @param SessionGateway<Quiz\Session> $sessionGateway
     */
    public function __construct(private SessionGateway $sessionGateway)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Quiz;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        /** @var Player $player */
        $player = $user->getPlayer();

        /** @var Quiz $quiz */
        $quiz = $subject;

        return !$this->sessionGateway->hasFinished($player, $quiz);
    }
}
