<?php

declare(strict_types=1);

namespace App\Tests\Unit\Content\DataProvider;

use App\Content\DataProvider\ResponseProvider;
use App\Content\Entity\Quiz\Response;
use App\Content\Gateway\ResponseGateway;
use PHPUnit\Framework\TestCase;

final class ResponseProviderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnNullIfIdIsNotInt(): void
    {
        $responseGateway = self::createMock(ResponseGateway::class);
        $responseGateway->expects(self::never())->method('getResponseById');

        $responseProvider = new ResponseProvider($responseGateway);

        self::assertNull($responseProvider->getItem(Response::class, 'fail'));
    }

    /**
     * @test
     */
    public function shouldReturnResponse(): void
    {
        $response = new Response();

        $responseGateway = self::createMock(ResponseGateway::class);
        $responseGateway
            ->expects(self::once())
            ->method('getResponseById')
            ->with(self::equalTo(1))
            ->willReturn($response);

        $responseProvider = new ResponseProvider($responseGateway);

        self::assertTrue($responseProvider->supports($response::class));

        self::assertSame($response, $responseProvider->getItem(Response::class, 1));
    }
}
