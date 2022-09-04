<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Player;

use Doctrine\ORM\EntityManagerInterface;
use Generator;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use IncentiveFactory\IlEtaitUneFoisUnDev\Tests\HelpersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UpdatePasswordTest extends WebTestCase
{
    use HelpersTrait;

    public function testShouldUpdatePlayerInDb(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/players/update-password');
        $client->submitForm('Modifier', self::createFormData());

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var PasswordHasherInterface $passwordHasher */
        $passwordHasher = $client->getContainer()->get(PasswordHasherInterface::class);

        /** @var Player $player */
        $player = $entityManager->getRepository(Player::class)->findOneBy(['email' => 'player+1@email.com']);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects('/players/update-password');
        self::assertTrue($passwordHasher->verify($player->getPassword(), 'Password123!'));
    }

    /**
     * @dataProvider provideInvalidFormData
     *
     * @param array{
     *      'new_password[oldPassword]': string,
     *      'new_password[plainPassword]': string
     * } $formData
     */
    public function testShouldRaiseFormErrors(array $formData): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+11@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/players/update-password');
        $client->submitForm('Modifier', $formData);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Generator<string, array<array-key, array{
     *      'new_password[oldPassword]': string,
     *      'new_password[plainPassword]': string
     * }>>
     */
    public function provideInvalidFormData(): Generator
    {
        yield 'blank oldPassword' => [self::createFormData(oldPassword: '')];
        yield 'invalid oldPassword' => [self::createFormData(oldPassword: 'fail')];
        yield 'blank plainPassword' => [self::createFormData(plainPassword: '')];
        yield 'invalid plainPassword' => [self::createFormData(plainPassword: 'fail')];
    }

    /**
     * @return array{
     *      'new_password[oldPassword]': string,
     *      'new_password[plainPassword]': string
     * }
     */
    private static function createFormData(
        string $oldPassword = 'password',
        string $plainPassword = 'Password123!'
    ): array {
        return [
            'new_password[oldPassword]' => $oldPassword,
            'new_password[plainPassword]' => $plainPassword,
        ];
    }
}
