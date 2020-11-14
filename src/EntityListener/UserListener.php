<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function prePersist(User $user): void
    {
        $user->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            )
        );
    }
}
