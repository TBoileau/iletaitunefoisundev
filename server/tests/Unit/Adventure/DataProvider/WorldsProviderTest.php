<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\DataProvider;

use App\Adventure\DataProvider\WorldsProvider;
use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\NullAdapter;

final class WorldsProviderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnCollectionOfWorlds(): void
    {
        $world = new World();

        $worldGateway = self::createMock(WorldGateway::class);
        $worldGateway
            ->expects(self::once())
            ->method('getWorlds')
            ->willReturn([$world]);

        $worldsProvider = new WorldsProvider($worldGateway);

        self::assertTrue($worldsProvider->supports(World::class));

        self::assertEquals([$world], $worldsProvider->getCollection(World::class));
    }
}
