<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Security\Action\Register;
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
        yield 'security register' => [
            'route' => 'security_register',
            'path' => '/api/security/register',
            'methods' => [Request::METHOD_POST],
            'requirements' => [],
            'defaults' => [
                '_controller' => sprintf('%s', Register::class),
            ],
        ];
    }
}
