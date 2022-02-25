<?php

declare(strict_types=1);

namespace App\Adventure\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;

final class WorldsProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @param WorldGateway<World> $worldGateway
     */
    public function __construct(private WorldGateway $worldGateway)
    {
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<array-key, World>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        return $this->worldGateway->getWorlds();
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return World::class === $resourceClass;
    }
}
