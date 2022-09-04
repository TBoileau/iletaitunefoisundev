<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Player;

use Doctrine\ORM\EntityManagerInterface;
use Generator;
use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Tests\HelpersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class RegistrationTest extends WebTestCase
{
    use HelpersTrait;

    public function testShouldInsertPlayerInDbAndRedirectToIndex(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/players/register');
        $client->submitForm('S\'inscrire', self::createFormData());

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var PasswordHasherInterface $passwordHasher */
        $passwordHasher = $client->getContainer()->get(PasswordHasherInterface::class);

        /** @var ?Player $player */
        $player = $entityManager->getRepository(Player::class)->findOneBy(['email' => 'player@email.com']);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects('/');
        self::assertInstanceOf(Player::class, $player);
        self::assertTrue($passwordHasher->verify($player->getPassword(), 'Password123!'));
        self::assertSame('player', $player->getNickname());
        self::assertSame('player@email.com', $player->getEmail());
        self::assertSame(Gender::Female, $player->getGender());
        self::assertNull($player->getAvatar());
        self::assertNull($player->getRegisteredAt());
        self::assertNotNull($player->getRegistrationToken());
        self::assertNull($player->getForgottenPasswordExpiredAt());
        self::assertNull($player->getForgottenPasswordToken());
        self::assertEmailCount(1);
        self::assertEmailContains((string) $player->getRegistrationToken());
    }

    /**
     * @dataProvider provideInvalidFormData
     *
     * @param array{
     *      'registration[email]': string,
     *      'registration[nickname]': string,
     *      'registration[gender]': string,
     *      'registration[plainPassword]': string
     * } $formData
     */
    public function testShouldRaiseFormErrors(array $formData): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/players/register');
        $client->submitForm('S\'inscrire', $formData);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Generator<string, array<array-key, array{
     *      'registration[email]': string,
     *      'registration[nickname]': string,
     *      'registration[gender]': string,
     *      'registration[plainPassword]': string
     * }>>
     */
    public function provideInvalidFormData(): Generator
    {
        yield 'blank email' => [self::createFormData(email: '')];
        yield 'invalid email' => [self::createFormData(email: 'fail')];
        yield 'used email' => [self::createFormData(email: 'player+1@email.com')];
        yield 'blank nickname' => [self::createFormData(nickname: '')];
        yield 'blank plainPassword' => [self::createFormData(plainPassword: '')];
        yield 'invalid plainPassword' => [self::createFormData(plainPassword: 'fail')];
    }

    /**
     * @return array{
     *      'registration[email]': string,
     *      'registration[nickname]': string,
     *      'registration[gender]': string,
     *      'registration[plainPassword]': string
     * }
     */
    private static function createFormData(
        string $email = 'player@email.com',
        string $nickname = 'player',
        string $gender = 'Joueuse',
        string $plainPassword = 'Password123!'
    ): array {
        return [
            'registration[email]' => $email,
            'registration[nickname]' => $nickname,
            'registration[gender]' => $gender,
            'registration[plainPassword]' => $plainPassword,
        ];
    }
}
