<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\WorldRepository;
use App\Adventure\Entity\World;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class WorldRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function shouldReturnWorlds(): void
    {
        self::bootKernel();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        /** @var non-empty-array<array-key, World> $worlds */
        $worlds = $worldRepository->getWorlds();

        self::assertCount(1, $worlds);
        self::assertCount(5, $worlds[0]->getContinents());
        foreach ($worlds[0]->getContinents() as $continent) {
            self::assertCount(5, $continent->getRegions());
        }
    }
}
