<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Security\Voter;

use IncentiveFactory\Domain\Path\CheckIfPathHasBegun\PathBegan;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Shared\Query\QueryBus;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class TrainingVoter extends Voter
{
    public const BEGIN = 'begin';

    public function __construct(private QueryBus $queryBus)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Training && in_array($attribute, [self::BEGIN], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false; // @codeCoverageIgnore
        }

        $player = $user->player;

        /**
         * @var Training $subject
         */

        /** @var bool $hasBegan */
        $hasBegan = $this->queryBus->fetch(new PathBegan($player, $subject));

        return !$hasBegan;
    }
}
