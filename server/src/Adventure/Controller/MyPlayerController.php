<?php

declare(strict_types=1);

namespace App\Adventure\Controller;

use App\Adventure\Entity\Player;
use App\Security\Entity\User;
use Symfony\Component\Security\Core\Security;

final class MyPlayerController
{
    public function __construct(private Security $security)
    {
    }

    public function __invoke(): ?Player
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getPlayer();
    }
}
