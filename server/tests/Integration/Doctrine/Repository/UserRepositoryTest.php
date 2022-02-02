<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Entity\User;
use App\Security\Doctrine\Repository\UserRepository;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Ulid;

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
        self::assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function findUserByEmailShouldReturnUser(): void
    {
        self::bootKernel();

        /** @var UserRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->findUserByEmail('user+1@email.com');

        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function registerShouldInsertUserInDatabase(): void
    {
        self::bootKernel();

        /** @var UserRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        /** @var UlidGeneratorInterface $uuidGenerator */
        $uuidGenerator = self::getContainer()->get(UlidGeneratorInterface::class);

        $user = new User();
        $user->setId($uuidGenerator->generate());
        $user->setEmail('user+6@email.com');
        $user->setPassword('password');

        $userRepository->register($user);

        $user = $userRepository->loadUserByIdentifier('user+6@email.com');

        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
        self::assertTrue(Ulid::isValid((string) $user->getId()));
        self::assertSame('user+6@email.com', $user->getEmail());
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
     * @return Generator<string, array{email: string, unique: bool}>
     */
    public function provideEmailAndUniqueState(): Generator
    {
        yield 'unique' => ['email' => 'user+6@email.com', 'unique' => true];
        yield 'non unique' => ['email' => 'user+1@email.com', 'unique' => false];
    }
}
