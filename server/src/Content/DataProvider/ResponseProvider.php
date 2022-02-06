<?php

declare(strict_types=1);

namespace App\Content\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Content\Entity\Quiz\Response;
use App\Content\Gateway\ResponseGateway;

final class ResponseProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @param ResponseGateway<Response> $responseGateway
     */
    public function __construct(private ResponseGateway $responseGateway)
    {
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Response
    {
        if (!is_int($id)) {
            return null;
        }

        return $this->responseGateway->getResponseById($id);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Response::class === $resourceClass;
    }
}
