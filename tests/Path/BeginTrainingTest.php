<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Path;

use Doctrine\ORM\EntityManagerInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class BeginTrainingTest extends WebTestCase
{
    public function testShouldBeganTrainingAndInsertPathInDb(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/paths/trainings/training+3/begin');
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects('/paths/trainings/training+3');
        $client->followRedirect();

        self::assertSelectorTextContains('h1', 'Training 3');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        $path = $entityManager->getRepository(Path::class)->createQueryBuilder('p')
            ->join('p.player', 'player')
            ->join('p.training', 'training')
            ->where('player.email = :player_email')
            ->andWhere('training.slug = :training_slug')
            ->setParameter('player_email', 'player+1@email.com')
            ->setParameter('training_slug', 'training+3')
            ->getQuery()
            ->getSingleResult();

        self::assertInstanceOf(Path::class, $path);
    }

    public function testShouldRaise403DueToTraingAlreadyBegan(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/paths/trainings/training+1/begin');
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
