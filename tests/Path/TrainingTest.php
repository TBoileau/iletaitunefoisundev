<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Path;

use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class TrainingTest extends WebTestCase
{
    public function testShouldListTrainings(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/paths/trainings/training+1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1', 'Training 1');
    }
}