<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Domain\Security\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

final class UserRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function loadUserByIdentifierShouldReturnUser(): void
    {
        self::bootKernel();

        /** @var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->loadUserByIdentifier('user+1@email.com');

        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function registerShouldInsertUserInDatabase(): void
    {
        self::bootKernel();

        /** @var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = new User();
        $user->setEmail('user+6@email.com');
        $user->setPassword('password');

        $userRepository->register($user);

        $user = $userRepository->loadUserByIdentifier('user+6@email.com');

        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
        self::assertInstanceOf(Uuid::class, $user->getId());
        self::assertSame('user+6@email.com', $user->getEmail());
        self::assertSame('password', $user->getPassword());
    }
}
