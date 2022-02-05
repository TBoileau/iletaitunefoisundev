<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\Factory\UuidV6Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV6;

class UuidFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createShouldReturnAUuidV6(): void
    {
        $uuidFactory = new UuidV6Factory();

        self::assertInstanceOf(UuidV6::class, $uuidFactory->create());
    }
}
