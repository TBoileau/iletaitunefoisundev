<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Controller\SecurityController;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class RoutingTest extends KernelTestCase
{
    /**
     * @test
     *
     * @param array<array-key, string> $methods
     * @param array<string, string>    $requirements
     * @param array<string, mixed>     $defaults
     *
     * @dataProvider provideRoutes
     */
    public function shouldMatch(string $route, string $path, array $methods, array $requirements = [], array $defaults = []): void
    {
        self::bootKernel();

        /** @var RouterInterface $router */
        $router = self::getContainer()->get(RouterInterface::class);

        $route = $router->getRouteCollection()->get($route);

        self::assertNotNull($route);
        self::assertSame($path, $route->getPath());
        self::assertEquals($methods, $route->getMethods());
        self::assertEquals($requirements, $route->getRequirements());
        self::assertEquals($defaults, $route->getDefaults());
    }

    /**
     * @return Generator<
     *      string,
     *      array{
     *          route: string,
     *          path: string,
     *          methods: array<array-key, string>,
     *          requirements: array<array-key, string>,
     *          defaults: array<array-key, mixed>
     *      }
     * >
     */
    public function provideRoutes(): Generator
    {
        yield 'security login' => [
            'route' => 'security_login',
            'path' => '/login',
            'methods' => [Request::METHOD_GET, Request::METHOD_POST],
            'requirements' => [],
            'defaults' => [
                '_controller' => sprintf('%s::%s', SecurityController::class, 'login'),
            ],
        ];
        yield 'security logout' => [
            'route' => 'security_logout',
            'path' => '/logout',
            'methods' => [Request::METHOD_GET],
            'requirements' => [],
            'defaults' => [
                '_controller' => sprintf('%s::%s', SecurityController::class, 'logout'),
            ],
        ];
    }
}
