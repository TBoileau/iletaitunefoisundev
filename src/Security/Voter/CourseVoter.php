<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Security\Voter;

use IncentiveFactory\Domain\Path\CheckIfCourseHasBegun\CourseBegan;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Shared\Query\QueryBus;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CourseVoter extends Voter
{
    public const BEGIN = 'begin';

    public function __construct(private QueryBus $queryBus)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Course && in_array($attribute, [self::BEGIN], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false; // @codeCoverageIgnore
        }

        $player = $user->player;

        /**
         * @var Course $subject
         * @var bool   $hasBegan
         */
        $hasBegan = $this->queryBus->fetch(new CourseBegan($player, $subject));

        return !$hasBegan;
    }
}
