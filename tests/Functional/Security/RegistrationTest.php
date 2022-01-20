<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Domain\Security\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final class RegistrationTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldRegisterAnUser(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/register');

        self::assertResponseIsSuccessful();

        $client->enableProfiler();

        $client->submitForm('S\'inscrire', self::createData());

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var UserRepository<User> $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->loadUserByIdentifier('user+6@email.com');

        /** @var UserPasswordHasherInterface $userPasswordHasher */
        $userPasswordHasher = $client->getContainer()->get(UserPasswordHasherInterface::class);

        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
        self::assertSame('user+6@email.com', $user->getEmail());
        self::assertTrue($userPasswordHasher->isPasswordValid($user, 'Password123!'));
        self::assertTrue(Uuid::isValid((string) $user->getId()));

        $client->followRedirect();

        self::assertRouteSame('security_login');
    }

    /**
     * @param array<string, string> $formData
     *
     * @test
     *
     * @dataProvider provideInvalidData
     */
    public function shouldNotRegisterDueToInvalidData(array $formData): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/register');

        self::assertResponseIsSuccessful();

        $client->enableProfiler();

        $client->submitForm('S\'inscrire', $formData);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        self::assertRouteSame('security_register');
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        yield 'invalid email' => [self::createData(['registration[email]' => 'fail'])];
        yield 'non unique email' => [self::createData(['registration[email]' => 'user+1@email.com'])];
        yield 'empty email' => [self::createData(['registration[email]' => ''])];
        yield 'wrong plain password' => [self::createData(['registration[plainPassword]' => 'fail'])];
        yield 'empty plain password' => [self::createData(['registration[plainPassword]' => ''])];
        yield 'empty csrf' => [self::createData(['registration[_token]' => ''])];
        yield 'wrong csrf' => [self::createData(['registration[_token]' => 'fail'])];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(array $extra = []): array
    {
        return $extra + [
                'registration[email]' => 'user+6@email.com',
                'registration[plainPassword]' => 'Password123!',
            ];
    }
}
