<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Repository\WorldRepository;
use App\Security\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class WorldRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function loadUserByIdentifierShouldReturnUser(): void
    {
        self::bootKernel();

        /** @var WorldRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(WorldRepository::class);

        $worlds = $userRepository->getWorlds();

        self::assertCount(1, $worlds);
    }
}
