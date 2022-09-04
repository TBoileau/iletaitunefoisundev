<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Security\Voter;

use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class PathVoter extends Voter
{
    public const SHOW = 'show';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Path && in_array($attribute, [self::SHOW], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false; // @codeCoverageIgnore
        }

        /**
         * @var Path   $subject
         * @var Player $player
         */
        $player = $subject->player();

        return $player->id()->equals($user->player->id());
    }
}
