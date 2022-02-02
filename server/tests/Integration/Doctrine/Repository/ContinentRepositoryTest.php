<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\ContinentRepository;
use App\Adventure\Doctrine\Repository\WorldRepository;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Ulid;

final class ContinentRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function getContinentsByWorldShouldReturnFiveContinents(): void
    {
        self::bootKernel();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        /** @var World $world */
        $world = $worldRepository->findOneBy(['name' => 'Monde']);

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = self::getContainer()->get(ContinentRepository::class);

        $continent = $continentRepository->getContinentsByWorld((string) $world->getId());

        self::assertCount(5, $continent);
    }

    /**
     * @test
     */
    public function getContinentByIdShouldReturnContinent(): void
    {
        self::bootKernel();

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = self::getContainer()->get(ContinentRepository::class);

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        /** @var World $world */
        $world = $worldRepository->findOneBy([]);

        $id = new Ulid();

        $continent = new Continent();
        $continent->setId($id);
        $continent->setName('Continent 0');
        $continent->setWorld($world);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($continent);

        $continent = $continentRepository->getContinentById((string) $id);

        self::assertEquals('Continent 0', $continent->getName());
        self::assertEquals($world, $continent->getWorld());
    }

    /**
     * @test
     */
    public function getContinentByIdShouldRaiseAnException(): void
    {
        self::bootKernel();

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = self::getContainer()->get(ContinentRepository::class);

        $id = new Ulid();

        self::expectException(InvalidArgumentException::class);
        $continentRepository->getContinentById((string) $id);
    }
}
