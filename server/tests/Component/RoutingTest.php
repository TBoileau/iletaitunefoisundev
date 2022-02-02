<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Adventure\Action\Continent\GetContinentAction;
use App\Adventure\Action\Continent\GetContinentsByWorldAction;
use App\Adventure\Action\Quest\GetQuestAction;
use App\Adventure\Action\Quest\GetQuestsByRegionAction;
use App\Adventure\Action\Quest\GetRelativesByQuestAction;
use App\Adventure\Action\Region\GetRegionAction;
use App\Adventure\Action\Region\GetRegionsByContinentAction;
use App\Adventure\Action\World\GetWorldAction;
use App\Adventure\Action\World\GetWorldsAction;
use App\Security\Action\RegisterAction;
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
                '_controller' => sprintf('%s', RegisterAction::class),
            ],
        ];
        yield 'adventure get worlds' => [
            'route' => 'adventure_get_worlds',
            'path' => '/api/adventure/worlds',
            'methods' => [Request::METHOD_GET],
            'requirements' => [],
            'defaults' => [
                '_controller' => sprintf('%s', GetWorldsAction::class),
            ],
        ];
        yield 'adventure get continents by world' => [
            'route' => 'adventure_get_continents_by_world',
            'path' => '/api/adventure/worlds/{id}/continents',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetContinentsByWorldAction::class),
            ],
        ];
        yield 'adventure get regions by continent' => [
            'route' => 'adventure_get_regions_by_continent',
            'path' => '/api/adventure/continents/{id}/regions',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetRegionsByContinentAction::class),
            ],
        ];
        yield 'adventure get region' => [
            'route' => 'adventure_get_region',
            'path' => '/api/adventure/regions/{id}',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetRegionAction::class),
            ],
        ];
        yield 'adventure get continent' => [
            'route' => 'adventure_get_continent',
            'path' => '/api/adventure/continents/{id}',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetContinentAction::class),
            ],
        ];
        yield 'adventure get world' => [
            'route' => 'adventure_get_world',
            'path' => '/api/adventure/worlds/{id}',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetWorldAction::class),
            ],
        ];
        yield 'adventure get quests by region' => [
            'route' => 'adventure_get_quests_by_region',
            'path' => '/api/adventure/regions/{id}/quests',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetQuestsByRegionAction::class),
            ],
        ];
        yield 'adventure get quest' => [
            'route' => 'adventure_get_quest',
            'path' => '/api/adventure/quests/{id}',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetQuestAction::class),
            ],
        ];
        yield 'adventure get relatives by quest' => [
            'route' => 'adventure_get_relatives_by_quest',
            'path' => '/api/adventure/quests/{id}/relatives',
            'methods' => [Request::METHOD_GET],
            'requirements' => ['id' => '[0-9A-Z]{26}'],
            'defaults' => [
                '_controller' => sprintf('%s', GetRelativesByQuestAction::class),
            ],
        ];
    }
}
