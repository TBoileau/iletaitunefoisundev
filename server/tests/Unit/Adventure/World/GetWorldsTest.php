<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\World;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use App\Adventure\UseCase\World\GetWorlds\GetWorlds;
use App\Adventure\UseCase\World\GetWorlds\GetWorldsHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetWorldsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetWorlds(): void
    {
        $getWorlds = new GetWorlds();

        $world = new World();
        $world->setId(new Ulid());
        $world->setName('Monde');

        $worldGateway = self::createMock(WorldGateway::class);
        $worldGateway
            ->expects(self::once())
            ->method('getWorlds')
            ->willReturn([$world]);

        $handler = new GetWorldsHandler($worldGateway);

        /** @var array<int, World> $worlds */
        $worlds = $handler($getWorlds);

        self::assertCount(1, $worlds);
        self::assertEquals('Monde', $worlds[0]->getName());
    }
}
