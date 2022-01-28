<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\ContinentCrudController;
use App\Admin\Entity\Administrator;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Adventure\Repository\ContinentRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class ContinentTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfContinents(): void
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
                ->setController(ContinentCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateContinent(): void
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
                ->setController(ContinentCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var World $world */
        $world = $entityManager->getRepository(World::class)->findOneBy(['name' => 'Monde']);

        $client->submitForm('Créer', [
            'Continent[name]' => 'Continent 6',
            'Continent[world]' => $world->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = $client->getContainer()->get(ContinentRepository::class);

        $continent = $continentRepository->findOneBy(['name' => 'Continent 6']);

        self::assertNotNull($continent);
        self::assertSame('Continent 6', $continent->getName());
        self::assertEquals($world->getId(), $continent->getWorld()->getId());
        self::assertTrue(Ulid::isValid((string) $continent->getId()));
        self::assertCount(0, $continent->getRegions());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfContinent(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Continent $continent */
        $continent = $entityManager->getRepository(Continent::class)->findOneBy(['name' => 'Continent 1']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(ContinentCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($continent->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateContinent(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var Continent $continent */
        $continent = $entityManager->getRepository(Continent::class)->findOneBy(['name' => 'Continent 1']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(ContinentCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $continent->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var World $world */
        $world = $entityManager->getRepository(World::class)->findOneBy(['name' => 'Monde']);

        $client->submitForm('Sauvegarder les modifications', [
            'Continent[name]' => 'Continent 0',
            'Continent[world]' => $world->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = $client->getContainer()->get(ContinentRepository::class);

        $continent = $continentRepository->findOneBy(['name' => 'Continent 0']);

        self::assertNotNull($continent);
        self::assertSame('Continent 0', $continent->getName());
        self::assertEquals($world->getId(), $continent->getWorld()->getId());
        self::assertTrue(Ulid::isValid((string) $continent->getId()));
        self::assertCount(5, $continent->getRegions());
    }
}
