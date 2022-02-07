<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Security\Doctrine\Repository\UserRepository;
use App\Security\Entity\User;
use App\Security\Factory\UuidV6Factory;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ResetForgottenPasswordTest extends ApiTestCase
{
    private HttpClientInterface $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
        unset($this->entityManager);
    }

    /**
     * @test
     * @dataProvider provideValidData
     *
     * @param array<string, string> $data
     */
    public function shouldAcceptThePostRequestForValidData(array $data): void
    {
        $this->updateUserForgottenPasswordTokenFromData($data);

        $response = $this->client->request(
            Request::METHOD_POST,
            '/api/security/forgotten-password/reset',
            [
                'json' => $data,
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
        self::assertEquals('', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldRejectForIncorrectTokenForUser(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/security/forgotten-password/reset',
            [
                'json' => self::createData(
                    'user+1@email.com',
                    'Password456!',
                    (new UuidV6Factory())->create()->toRfc4122()
                ),
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function shouldRejectForUnknownEmailWithIncorrectTokenForUser(): void
    {
        $response = $this->client->request(
            Request::METHOD_POST,
            '/api/security/forgotten-password/reset',
            [
                'json' => self::createData(
                    'user+unknown@email.com',
                    'Password456!',
                    (new UuidV6Factory())->create()->toRfc4122()
                ),
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );

        self::assertJsonContains(['violations' => [['propertyPath' => 'email']]]);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @dataProvider provideInvalidData
     *
     * @param array<string, string> $data
     */
    public function shouldRejectForInvalidData(array $data): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/security/forgotten-password/reset',
            [
                'json' => $data,
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideValidData(): Generator
    {
        yield 'Valid Data : known email' => [self::createData(
            'user+1@email.com',
            'Password456!',
            (new UuidV6Factory())->create()->toRfc4122()),
        ];
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        $correctEmail = 'user+1@email.com';
        $correctPassword = 'Password456!';
        $correctForgottenPasswordToken = (new UuidV6Factory())->create()->toRfc4122();

        yield 'invalid email' => [self::createData(
            'notanemail',
            $correctPassword,
            $correctForgottenPasswordToken
        )];
        yield 'unknown email' => [self::createData(
            'user+unknown@email.com',
            $correctPassword,
            $correctForgottenPasswordToken
        )];
        yield 'empty email' => [self::createData(
            '',
            $correctPassword,
            $correctForgottenPasswordToken
        )];
        yield 'blank Password' => [self::createData(
            $correctEmail,
            '',
            $correctForgottenPasswordToken
        )];
        yield 'incorrect Password' => [self::createData(
            $correctEmail,
            'notacorrectpassword',
            $correctForgottenPasswordToken
        )];
        yield 'blank token' => [self::createData(
            $correctEmail,
            $correctPassword,
            ''
        )];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(
        string $email,
        string $password,
        string $forgottenPasswordToken,
        array $extra = []
    ): array {
        return $extra + [
                'email' => $email,
                'plainPassword' => $password,
                'forgottenPasswordToken' => $forgottenPasswordToken,
            ];
    }

    /**
     * @param array<string, string> $data
     */
    private function updateUserForgottenPasswordTokenFromData(array $data): void
    {
        /** @var UserRepository<User> $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var ?User $user */
        $user = $userRepository->loadUserByIdentifier($data['email']);

        if (null === $user) {
            return;
        }

        $user->setForgottenPasswordToken($data['forgottenPasswordToken']);
        $userRepository->update($user);
    }
}
