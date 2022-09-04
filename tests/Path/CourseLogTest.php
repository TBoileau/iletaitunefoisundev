<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Path;

use Doctrine\ORM\EntityManagerInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Course;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;
use IncentiveFactory\IlEtaitUneFoisUnDev\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class CourseLogTest extends WebTestCase
{
    public function testShouldShowCourseLog(): void
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

        $client->request(Request::METHOD_GET, sprintf('/paths/%s/courses/%s/course-log', $path->getId(), $course->getSlug()));
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1', 'Course 1');
    }

    public function testShouldRaiseA404DueToPathNotFound(): void
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

        $client->request(Request::METHOD_GET, sprintf('/paths/01GC52Q2RD6MC4CHJXGYT3SDDC/courses/%s/course-log', $course->getSlug()));
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testShouldRaiseA404DueToCourseNotFound(): void
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

        $client->request(Request::METHOD_GET, sprintf('/paths/%s/courses/fail/course-log', $path->getId()));
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testShouldRaiseA404DueToCourseLogNotFound(): void
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

        $client->request(Request::METHOD_GET, sprintf('/paths/%s/courses/%s/course-log', $path->getId(), $course->getSlug()));
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
