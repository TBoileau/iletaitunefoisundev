<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\WorldCrudController;
use App\Admin\Entity\Administrator;
use App\Adventure\Entity\World;
use App\Adventure\Repository\WorldRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class WorldTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfWorlds(): void
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
                ->setController(WorldCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateWorld(): void
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
                ->setController(WorldCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'World[name]' => 'World 2',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = $client->getContainer()->get(WorldRepository::class);

        $world = $worldRepository->findOneBy(['name' => 'World 2']);

        self::assertNotNull($world);
        self::assertSame('World 2', $world->getName());
        self::assertTrue(Ulid::isValid((string) $world->getId()));
        self::assertCount(0, $world->getContinents());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfWorld(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var World $world */
        $world = $entityManager->getRepository(World::class)->findOneBy(['name' => 'Monde']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(WorldCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($world->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateWorld(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var World $world */
        $world = $entityManager->getRepository(World::class)->findOneBy(['name' => 'Monde']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(WorldCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $world->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        $client->submitForm('Sauvegarder les modifications', [
            'World[name]' => 'World 0',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = $client->getContainer()->get(WorldRepository::class);

        $world = $worldRepository->findOneBy(['name' => 'World 0']);

        self::assertNotNull($world);
        self::assertSame('World 0', $world->getName());
        self::assertCount(5, $world->getContinents());
    }
}
