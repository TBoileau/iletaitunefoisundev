<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Player;

use Doctrine\ORM\EntityManagerInterface;
use Generator;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Tests\HelpersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class ValidRegistrationTest extends WebTestCase
{
    use HelpersTrait;

    public function testShouldValidRegistrationAndRedirectToLogin(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Player $player */
        $player = $entityManager->getRepository(Player::class)->findOneBy(['email' => 'player+11@email.com']);

        $client->request(Request::METHOD_GET, sprintf('/players/valid-registration/%s', $player->getRegistrationToken()));

        $entityManager->refresh($player);

        self::assertNotNull($player->getRegisteredAt());
        self::assertNull($player->getRegistrationToken());
    }

    /**
     * @dataProvider provideInvalidRegistrationToken
     */
    public function testShouldRaiseErrors(string $registrationToken): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, sprintf('/players/valid-registration/%s', $registrationToken));
        self::assertFlashBagContains('error', 'Une erreur est survenue lors de la validation de votre inscription.');
    }

    /**
     * @return Generator<string, array<array-key, string>>
     */
    public function provideInvalidRegistrationToken(): Generator
    {
        yield 'invalid registrationToken' => ['fail'];
        yield 'non existing registrationToken' => ['0098940c-12e7-4de2-b7ea-05f2dade8f0a'];
    }
}
