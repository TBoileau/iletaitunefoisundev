<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Continent;

use App\Adventure\Entity\Continent;
use App\Adventure\Gateway\ContinentGateway;
use App\Adventure\UseCase\Continent\GetContinent\GetContinent;
use App\Adventure\UseCase\Continent\GetContinent\GetContinentHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetContinentTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetContinent(): void
    {
        $continentId = new Ulid();

        $continent = new Continent();
        $continent->setId($continentId);
        $continent->setName('Continent');

        $continentGateway = self::createMock(ContinentGateway::class);
        $continentGateway
            ->expects(self::once())
            ->method('getContinentById')
            ->with(self::equalTo((string) $continentId))
            ->willReturn($continent);

        $handler = new GetContinentHandler($continentGateway);

        $getContinent = new GetContinent((string) $continentId);

        $continent = $handler($getContinent);

        self::assertEquals('Continent', $continent->getName());
    }
}
