<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\ContinentRepository;
use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Ulid;

final class RegionRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function getRegionsByContinentShouldReturnFiveRegions(): void
    {
        self::bootKernel();

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = self::getContainer()->get(ContinentRepository::class);

        /** @var Continent $continent */
        $continent = $continentRepository->findOneBy(['name' => 'Continent 1']);

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = self::getContainer()->get(RegionRepository::class);

        $region = $regionRepository->getRegionsByContinent((string) $continent->getId());

        self::assertCount(5, $region);
    }

    /**
     * @test
     */
    public function getRegionByIdShouldReturnRegion(): void
    {
        self::bootKernel();

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = self::getContainer()->get(RegionRepository::class);

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = self::getContainer()->get(ContinentRepository::class);

        /** @var Continent $continent */
        $continent = $continentRepository->findOneBy([]);

        $id = new Ulid();

        $region = new Region();
        $region->setId($id);
        $region->setName('Region 0');
        $region->setContinent($continent);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($region);

        $region = $regionRepository->getRegionById((string) $id);

        self::assertEquals('Region 0', $region->getName());
        self::assertEquals($continent, $region->getContinent());
    }

    /**
     * @test
     */
    public function getRegionByIdShouldRaiseAnException(): void
    {
        self::bootKernel();

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = self::getContainer()->get(RegionRepository::class);

        $id = new Ulid();

        self::expectException(InvalidArgumentException::class);
        $regionRepository->getRegionById((string) $id);
    }
}
