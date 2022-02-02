<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\WorldRepository;
use App\Adventure\Entity\World;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Ulid;

final class WorldRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function getWorldsShouldReturnsOneWorld(): void
    {
        self::bootKernel();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        $worlds = $worldRepository->getWorlds();

        self::assertCount(1, $worlds);
    }

    /**
     * @test
     */
    public function getWorldByIdShouldReturnWorld(): void
    {
        self::bootKernel();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        $id = new Ulid();

        $world = new World();
        $world->setId($id);
        $world->setName('World 0');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($world);

        $world = $worldRepository->getWorldById((string) $id);

        self::assertEquals('World 0', $world->getName());
    }

    /**
     * @test
     */
    public function getWorldByIdShouldRaiseAnException(): void
    {
        self::bootKernel();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        $id = new Ulid();

        self::expectException(InvalidArgumentException::class);
        $worldRepository->getWorldById((string) $id);
    }
}
