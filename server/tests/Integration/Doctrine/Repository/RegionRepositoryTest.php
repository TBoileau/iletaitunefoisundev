<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\ContinentRepository;
use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

        $region = $regionRepository->getRegionsByContinent($continent);

        self::assertCount(5, $region);
    }
}
