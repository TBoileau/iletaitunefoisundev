<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
use App\Tests\Functional\ApiTestCase;
use Generator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

final class RegistrationTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldRegisterAnUser(): void
    {
        $client = self::post('/api/security/register', self::createData());

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var UserRepository<User> $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->loadUserByIdentifier('user+6@email.com');

        /** @var UserPasswordHasherInterface $userPasswordHasher */
        $userPasswordHasher = $client->getContainer()->get(UserPasswordHasherInterface::class);

        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
        self::assertSame('user+6@email.com', $user->getEmail());
        self::assertTrue($userPasswordHasher->isPasswordValid($user, 'Password123!'));
        self::assertTrue(Ulid::isValid((string) $user->getId()));
    }

    /**
     * @param array<string, string> $data
     *
     * @test
     *
     * @dataProvider provideInvalidData
     */
    public function shouldNotRegisterDueToInvalidData(array $data): void
    {
        self::post('/api/security/register', $data);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        yield 'invalid email' => [self::createData(['email' => 'fail'])];
        yield 'non unique email' => [self::createData(['email' => 'user+1@email.com'])];
        yield 'empty email' => [self::createData(['email' => ''])];
        yield 'wrong plain password' => [self::createData(['plainPassword' => 'fail'])];
        yield 'empty plain password' => [self::createData(['plainPassword' => ''])];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(array $extra = []): array
    {
        return $extra + [
                'email' => 'user+6@email.com',
                'plainPassword' => 'Password123!',
            ];
    }
}
