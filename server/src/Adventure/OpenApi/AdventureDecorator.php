<?php

declare(strict_types=1);

namespace App\Adventure\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

final class AdventureDecorator implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    /**
     * @param array<array-key, mixed> $context
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $newPaths = new Model\Paths();

        /**
         * @var string         $iri
         * @var Model\PathItem $path
         */
        foreach ($openApi->getPaths()->getPaths() as $iri => $path) {
            if (
                '/api/adventure/regions/{id}' === $iri
                || '/api/adventure/worlds/{id}' === $iri
            ) {
                continue;
            }

            $newPaths->addPath($iri, $path);
        }

        return $openApi->withPaths($newPaths);
    }
}
