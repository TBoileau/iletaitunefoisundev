<?php

declare(strict_types=1);

namespace App\Security\UserChecker;

use App\Entity\User;
use App\Security\Exception\AccountSuspendedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return; // @codeCoverageIgnore
        }

        if ($user->isSuspended()) {
            throw new AccountSuspendedException("Your account is suspended.");
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function checkPostAuth(UserInterface $user): void
    {
    }
}
