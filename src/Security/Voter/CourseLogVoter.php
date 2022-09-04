<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Security\Voter;

use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CourseLogVoter extends Voter
{
    public const COMPLETE = 'complete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof CourseLog && in_array($attribute, [self::COMPLETE], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false; // @codeCoverageIgnore
        }

        $player = $user->player;

        /**
         * @var CourseLog $subject
         */
        if ($subject->path()->player() === $player) {
            return false;
        }

        return !$subject->hasCompleted();
    }
}
