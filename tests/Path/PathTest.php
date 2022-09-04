<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Path;

use Doctrine\ORM\EntityManagerInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class PathTest extends WebTestCase
{
    public function testShouldShowPath(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Training $training */
        $training = $entityManager->getRepository(Training::class)->findOneBy(['slug' => 'training+1']);

        /** @var Path $path */
        $path = $entityManager->getRepository(Path::class)->findOneBy([
            'player' => $user->player->id()->toBinary(),
            'training' => $training,
        ]);

        $client->request(Request::METHOD_GET, sprintf('/paths/%s', $path->getId()));
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1', 'Training 1');
    }

    public function testShouldRaiseA403(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Training $training */
        $training = $entityManager->getRepository(Training::class)->findOneBy(['slug' => 'training+1']);

        /** @var Player $player */
        $player = $entityManager->getRepository(Player::class)->findOneBy(['email' => 'player+2@email.com']);

        /** @var Path $path */
        $path = $entityManager->getRepository(Path::class)->findOneBy([
            'player' => $player,
            'training' => $training,
        ]);

        $client->request(Request::METHOD_GET, sprintf('/paths/%s', $path->getId()));
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testShouldRaiseA404(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/paths/01GC4VG1TC298X8NQ01W2DXQX4');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
