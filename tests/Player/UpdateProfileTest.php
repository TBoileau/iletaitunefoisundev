<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Player;

use Doctrine\ORM\EntityManagerInterface;
use Generator;
use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use IncentiveFactory\IlEtaitUneFoisUnDev\Tests\HelpersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UpdateProfileTest extends WebTestCase
{
    use HelpersTrait;

    public function testShouldUpdatePlayerInDb(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+11@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/players/update-profile');
        $client->submitForm('Modifier', self::createFormData(avatarFile: self::fakeImage()));

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var ?Player $player */
        $player = $entityManager->getRepository(Player::class)->findOneBy(['email' => 'player@email.com']);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects('/players/update-profile');
        self::assertInstanceOf(Player::class, $player);
        self::assertSame('player', $player->getNickname());
        self::assertSame('player@email.com', $player->getEmail());
        self::assertSame(Gender::Female, $player->getGender());
        self::assertNotNull($player->getAvatar());
        self::assertFlashBagContains('success', 'Votre profil a bien été mis à jour.');
    }

    /**
     * @dataProvider provideInvalidFormData
     *
     * @param array{
     *      'profile[email]': string,
     *      'profile[nickname]': string,
     *      'profile[gender]': string,
     *      'profile[avatarFile]': ?File
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

        $client->request(Request::METHOD_GET, '/players/update-profile');
        $client->submitForm('Modifier', $formData);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Generator<string, array<array-key, array{
     *      'profile[email]': string,
     *      'profile[nickname]': string,
     *      'profile[gender]': string,
     *      'profile[avatarFile]': ?File
     * }>>
     */
    public function provideInvalidFormData(): Generator
    {
        yield 'blank email' => [self::createFormData(email: '')];
        yield 'invalid email' => [self::createFormData(email: 'fail')];
        yield 'used email' => [self::createFormData(email: 'player+2@email.com')];
        yield 'blank nickname' => [self::createFormData(nickname: '')];
        yield 'invalid avatarFile' => [self::createFormData(avatarFile: self::fakeFile())];
    }

    /**
     * @return array{
     *      'profile[email]': string,
     *      'profile[nickname]': string,
     *      'profile[gender]': string,
     *      'profile[avatarFile]': ?File
     * }
     */
    private static function createFormData(
        string $email = 'player@email.com',
        string $nickname = 'player',
        string $gender = 'Joueuse',
        ?File $avatarFile = null
    ): array {
        return [
            'profile[email]' => $email,
            'profile[nickname]' => $nickname,
            'profile[gender]' => $gender,
            'profile[avatarFile]' => $avatarFile,
        ];
    }
}
