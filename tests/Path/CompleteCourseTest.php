<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Path;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Course;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\CourseLog;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class CompleteCourseTest extends WebTestCase
{
    public function testShouldCompleteCourse(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Course $course */
        $course = $entityManager->getRepository(Course::class)->findOneBy(['slug' => 'course+1']);

        /** @var Training $training */
        $training = $entityManager->getRepository(Training::class)->findOneBy(['slug' => 'training+1']);

        /** @var Path $path */
        $path = $entityManager->getRepository(Path::class)->findOneBy([
            'player' => $user->player->id()->toBinary(),
            'training' => $training,
        ]);

        /** @var CourseLog $courseLog */
        $courseLog = $entityManager->getRepository(CourseLog::class)->findOneBy([
            'path' => $path,
            'course' => $course,
        ]);

        $client->request(Request::METHOD_GET, sprintf('/paths/course-logs/%s/complete', $courseLog->getId()));
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects(sprintf('/paths/%s', $path->getId()));

        $entityManager->refresh($courseLog);

        self::assertNotNull($courseLog->getCompletedAt());
    }

    public function testShouldRaiseA404DueToCourseLogNotFound(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, '/paths/course-logs/01GC52Q2RD6MC4CHJXGYT3SDDC/complete');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testShouldRaiseA403DueToCourseAlreadyComplete(): void
    {
        $client = static::createClient();

        /** @var UserProviderInterface $userProvider */
        $userProvider = $client->getContainer()->get(UserProviderInterface::class);

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier('player+1@email.com');

        $client->loginUser($user);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Course $course */
        $course = $entityManager->getRepository(Course::class)->findOneBy(['slug' => 'course+1']);

        /** @var Training $training */
        $training = $entityManager->getRepository(Training::class)->findOneBy(['slug' => 'training+1']);

        /** @var Path $path */
        $path = $entityManager->getRepository(Path::class)->findOneBy([
            'player' => $user->player->id()->toBinary(),
            'training' => $training,
        ]);

        /** @var CourseLog $courseLog */
        $courseLog = $entityManager->getRepository(CourseLog::class)->findOneBy([
            'path' => $path,
            'course' => $course,
        ]);

        $courseLog->setCompletedAt(new DateTimeImmutable());

        $entityManager->flush();

        $client->request(Request::METHOD_GET, sprintf('/paths/course-logs/%s/complete', $courseLog->getId()));
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
