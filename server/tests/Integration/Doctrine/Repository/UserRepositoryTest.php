<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Security\Doctrine\Repository\UserRepository;
use App\Security\Entity\User;
use Generator;
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

        /** @var UserRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->loadUserByIdentifier('user+1@email.com');

        self::assertNotNull($user);
    }

    /**
     * @test
     */
    public function registerShouldInsertUserInDatabase(): void
    {
        self::bootKernel();

        /** @var UserRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = new User();
        $user->setEmail('user+7@email.com');
        $user->setPassword('password');

        $userRepository->register($user);

        $user = $userRepository->loadUserByIdentifier('user+7@email.com');

        self::assertNotNull($user);
        self::assertSame('user+7@email.com', $user->getEmail());
        self::assertSame('password', $user->getPassword());
    }

    /**
     * @test
     *
     * @dataProvider provideEmailAndUniqueState
     */
    public function isUniqueEmailShouldReturn(string $email, bool $unique): void
    {
        self::bootKernel();

        /** @var UserRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        self::assertSame($unique, $userRepository->isUniqueEmail($email));
    }

    /**
     * @test
     */
    public function updateShouldUpdateUserInDB(): void
    {
        self::bootKernel();

        /** @var UserRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->loadUserByIdentifier('user+1@email.com');

        $newEmail = 'newEmail@email.com';
        $forgottenPasswordToken = Uuid::v6();
        $user->setEmail($newEmail);
        $user->setForgottenPasswordToken($forgottenPasswordToken->toRfc4122());

        $userRepository->update($user);

        $updateUser = $userRepository->loadUserByIdentifier($newEmail);

        self::assertNotNull($updateUser);
        self::assertSame($newEmail, $updateUser->getEmail());
        self::assertSame($forgottenPasswordToken->toRfc4122(), $updateUser->getForgottenPasswordToken());
    }

    /**
     * @return Generator<string, array{email: string, unique: bool}>
     */
    public function provideEmailAndUniqueState(): Generator
    {
        yield 'unique' => ['email' => 'user+7@email.com', 'unique' => true];
        yield 'non unique' => ['email' => 'user+1@email.com', 'unique' => false];
    }
}
