<?php

declare(strict_types=1);

namespace App\Adventure\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use Symfony\Contracts\Cache\CacheInterface;

final class WorldsProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @param WorldGateway<World> $worldGateway
     */
    public function __construct(private CacheInterface $cache, private WorldGateway $worldGateway)
    {
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<array-key, World>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        /** @var array<array-key, World> $worlds */
        $worlds = $this->cache->get(
            'adventure',
            fn (): array => $this->worldGateway->getWorlds()
        );

        return $worlds;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return World::class === $resourceClass;
    }
}
