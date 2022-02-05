<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\RegionCrudController;
use App\Admin\Entity\Administrator;
use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class RegionTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfRegions(): void
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
                ->setController(RegionCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldCreateRegion(): void
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
                ->setController(RegionCrudController::class)
                ->setAction(Action::NEW)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Continent $continent */
        $continent = $entityManager->getRepository(Continent::class)->findOneBy(['name' => 'Continent 1']);

        $client->submitForm('Créer', [
            'Region[name]' => 'Region 6',
            'Region[continent]' => $continent->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = $client->getContainer()->get(RegionRepository::class);

        $region = $regionRepository->findOneBy(['name' => 'Region 6']);

        self::assertNotNull($region);
        self::assertSame('Region 6', $region->getName());
        self::assertEquals($continent->getId(), $region->getContinent()->getId());
        self::assertCount(0, $region->getQuests());
    }

    /**
     * @test
     */
    public function shouldShowDetailOfRegion(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Region $region */
        $region = $entityManager->getRepository(Region::class)->findOneBy(['name' => 'Region 1']);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(RegionCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($region->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldUpdateRegion(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        /** @var Region $region */
        $region = $entityManager->getRepository(Region::class)->findOneBy(['name' => 'Region 1']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(RegionCrudController::class)
                ->setAction(Action::EDIT)
                ->setEntityId((string) $region->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Continent $continent */
        $continent = $entityManager->getRepository(Continent::class)->findOneBy(['name' => 'Continent 2']);

        $client->submitForm('Sauvegarder les modifications', [
            'Region[name]' => 'Region 0',
            'Region[continent]' => $continent->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = $client->getContainer()->get(RegionRepository::class);

        $region = $regionRepository->findOneBy(['name' => 'Region 0']);

        self::assertNotNull($region);
        self::assertSame('Region 0', $region->getName());
        self::assertEquals($continent->getId(), $region->getContinent()->getId());
        self::assertCount(5, $region->getQuests());
    }
}
