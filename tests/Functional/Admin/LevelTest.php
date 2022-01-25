<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\LevelCrudController;
use App\Adventure\Entity\Level;
use App\Adventure\Entity\Map;
use App\Adventure\Repository\LevelRepository;
use App\Adventure\Repository\MapRepository;
use App\Node\Entity\Course;
use App\Node\Repository\CourseRepository;
use App\Security\Entity\Administrator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class LevelTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfLevels(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(LevelCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateLevel(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(LevelCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var MapRepository<Map> $mapRepository */
        $mapRepository = $client->getContainer()->get(MapRepository::class);

        /** @var Map $map */
        $map = $mapRepository->findOneBy(['name' => 'Map 1']);

        /** @var LevelRepository<Level> $levelRepository */
        $levelRepository = $client->getContainer()->get(LevelRepository::class);

        /** @var Level $previous */
        $previous = $levelRepository->findOneBy(['order' => 10, 'map' => $map]);

        /** @var CourseRepository<Course> $courseRepository */
        $courseRepository = $client->getContainer()->get(CourseRepository::class);

        /** @var Course $course */
        $course = $courseRepository->findOneBy(['slug' => 'course-1']);

        $client->submitForm('Créer', [
            'Level[order]' => 11,
            'Level[map]' => $map->getId(),
            'Level[previous]' => $previous->getId(),
            'Level[course]' => $course->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var LevelRepository<Level> $levelRepository */
        $levelRepository = $client->getContainer()->get(LevelRepository::class);

        $level = $levelRepository->findOneBy(['order' => 11, 'map' => $map]);

        self::assertNotNull($level);
        self::assertTrue(Ulid::isValid((string) $level->getId()));
        self::assertNotNull($level->getNext());
        self::assertNotNull($level->getPrevious());
        self::assertEquals($previous->getId(), $level->getPrevious()->getId());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfLevel(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Level $level */
        $level = $entityManager->getRepository(Level::class)->findOneBy([]);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(LevelCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($level->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateLevel(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var MapRepository<Map> $mapRepository */
        $mapRepository = $client->getContainer()->get(MapRepository::class);

        /** @var Map $map = */
        $map = $mapRepository->findOneBy(['name' => 'Map 1']);

        /** @var LevelRepository<Level> $levelRepository */
        $levelRepository = $client->getContainer()->get(LevelRepository::class);

        /** @var Level $level */
        $level = $levelRepository->findOneBy(['order' => 10, 'map' => $map]);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(LevelCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $level->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Level $previous */
        $previous = $level->getPrevious();

        $client->submitForm('Sauvegarder les modifications', [
            'Level[order]' => 11,
            'Level[map]' => $map->getId(),
            'Level[previous]' => $previous->getId(),
            'Level[course]' => $level->getCourse()->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var LevelRepository<Level> $levelRepository */
        $levelRepository = $client->getContainer()->get(LevelRepository::class);

        $level = $levelRepository->findOneBy(['order' => 11, 'map' => $map]);

        self::assertNotNull($level);
        self::assertTrue(Ulid::isValid((string) $level->getId()));
        self::assertNotNull($level->getNext());
        self::assertNotNull($level->getPrevious());
    }
}
