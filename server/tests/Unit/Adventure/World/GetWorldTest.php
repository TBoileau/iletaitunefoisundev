<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\World;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use App\Adventure\UseCase\World\GetWorld\GetWorld;
use App\Adventure\UseCase\World\GetWorld\GetWorldHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetWorldTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetWorld(): void
    {
        $worldId = new Ulid();

        $world = new World();
        $world->setId($worldId);
        $world->setName('Monde');

        $worldGateway = self::createMock(WorldGateway::class);
        $worldGateway
            ->expects(self::once())
            ->method('getWorldById')
            ->with(self::equalTo((string) $worldId))
            ->willReturn($world);

        $handler = new GetWorldHandler($worldGateway);

        $getWorld = new GetWorld((string) $worldId);

        $world = $handler($getWorld);

        self::assertEquals('Monde', $world->getName());
    }
}
