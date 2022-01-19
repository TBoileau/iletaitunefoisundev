<?php

declare(strict_types=1);

namespace App\Tests\Smoke;

use Generator;

final class SecurityTest extends SmokeTestCase
{
    public function provideRoutes(): Generator
    {
        yield 'login' => ['path' => '/login'];
    }
}
