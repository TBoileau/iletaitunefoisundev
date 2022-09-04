<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Path;

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

final class BeginCourseTest extends WebTestCase
{
    public function testShouldBeganCourseAndInsertCourseLogInDb(): void
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
        $course = $entityManager->getRepository(Course::class)->findOneBy(['slug' => 'course+2']);

        /** @var Training $training */
        $training = $entityManager->getRepository(Training::class)->findOneBy(['slug' => 'training+1']);

        /** @var Path $path */
        $path = $entityManager->getRepository(Path::class)->findOneBy([
            'player' => $user->player->id()->toBinary(),
            'training' => $training,
        ]);

        $client->request(Request::METHOD_GET, sprintf('/paths/%s/courses/%s/begin', $path->getId(), $course->getSlug()));
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects('/paths/courses/course+2');
        $client->followRedirect();

        self::assertSelectorTextContains('h1', 'Course 2');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var ?CourseLog $courseLog */
        $courseLog = $entityManager->getRepository(CourseLog::class)->findOneBy(['course' => $course]);

        self::assertInstanceOf(CourseLog::class, $courseLog);
        self::assertNull($courseLog->getCompletedAt());
    }

    public function testShouldRaise403DueToCourseAlreadyBegan(): void
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

        $client->request(Request::METHOD_GET, sprintf('/paths/%s/courses/%s/begin', $path->getId(), $course->getSlug()));
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testShouldRaise404DueToPathNotFound(): void
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

        $client->request(Request::METHOD_GET, sprintf('/paths/01GC52Q2RD6MC4CHJXGYT3SDDC/courses/%s/begin', $course->getSlug()));
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testShouldRaise404DueToCourseNotFound(): void
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

        $client->request(Request::METHOD_GET, sprintf('/paths/%s/courses/fail/begin', $path->getId()));
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
