<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use App\Adventure\Message\RetrieveWorlds;
use App\Adventure\Query\RetrieveWorldsHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class RetrieveWorldsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetWorlds(): void
    {
        $retrieveWorlds = new RetrieveWorlds();

        $world = new World();
        $world->setId(new Ulid());
        $world->setName('Monde');

        $worldGateway = self::createMock(WorldGateway::class);
        $worldGateway
            ->expects(self::once())
            ->method('getWorlds')
            ->willReturn([$world]);

        $handler = new RetrieveWorldsHandler($worldGateway);

        /** @var array<int, World> $worlds */
        $worlds = $handler($retrieveWorlds);

        self::assertCount(1, $worlds);
        self::assertEquals('Monde', $worlds[0]->getName());
    }
}
