<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\MapCrudController;
use App\Adventure\Entity\Level;
use App\Adventure\Entity\Map;
use App\Adventure\Repository\MapRepository;
use App\Security\Entity\Administrator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class MapTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfMaps(): void
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
                ->setController(MapCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateMap(): void
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
                ->setController(MapCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var MapRepository<Map> $mapRepository */
        $mapRepository = $client->getContainer()->get(MapRepository::class);

        /** @var Level $previous */
        $previous = $mapRepository->findOneBy(['name' => 'Map 5']);

        $client->submitForm('Créer', [
            'Map[name]' => 'Map',
            'Map[previous]' => $previous->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var MapRepository<Map> $mapRepository */
        $mapRepository = $client->getContainer()->get(MapRepository::class);

        $map = $mapRepository->findOneBy(['name' => 'Map']);

        self::assertNotNull($map);
        self::assertSame('Map', $map->getName());
        self::assertTrue(Ulid::isValid((string) $map->getId()));
        self::assertNull($map->getStart());
        self::assertNull($map->getNext());
        self::assertNotNull($map->getPrevious());
        self::assertEquals($previous->getId(), $map->getPrevious()->getId());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfMap(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Map $map */
        $map = $entityManager->getRepository(Map::class)->findOneBy(['name' => 'Map 1']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(MapCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($map->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateMap(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var Map $map */
        $map = $entityManager->getRepository(Map::class)->findOneBy(['name' => 'Map 1']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(MapCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $map->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Level $level */
        $level = $entityManager->getRepository(Level::class)->findOneBy(['map' => $map, 'order' => 2]);

        $client->submitForm('Sauvegarder les modifications', [
            'Map[name]' => 'Map 0',
            'Map[start]' => $level->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var MapRepository<Map> $mapRepository */
        $mapRepository = $client->getContainer()->get(MapRepository::class);

        $map = $mapRepository->findOneBy(['name' => 'Map 0']);

        self::assertNotNull($map);
        self::assertSame('Map 0', $map->getName());
        self::assertTrue(Ulid::isValid((string) $map->getId()));
        self::assertNotNull($map->getStart());
        self::assertNotNull($map->getNext());
        self::assertNull($map->getPrevious());
    }
}
