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
    public function getWorldsShouldReturnsOneWorld(): void
    {
        self::bootKernel();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        $worlds = $worldRepository->getWorlds();

        self::assertCount(1, $worlds);
    }
}
