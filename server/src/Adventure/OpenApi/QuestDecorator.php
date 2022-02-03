<?php

declare(strict_types=1);

namespace App\Adventure\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

final class QuestDecorator implements OpenApiFactoryInterface
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

        $paths = $openApi->getPaths();

        /** @var Model\PathItem $pathItem */
        $pathItem = $paths->getPath('/api/adventure/quests/{id}/finish');

        /** @var Model\Operation $post */
        $post = $pathItem->getPost();

        $post = $post->withSummary('Finish a Quest.');

        $post = $post->withDescription('Finish a Quest by saving a new checkpoint.');

        /** @var Model\RequestBody $requestBody */
        $requestBody = $post->getRequestBody();

        $content = $requestBody->getContent();

        $content['application/json'] = new Model\MediaType(
            new \ArrayObject([
                'type' => 'object',
                'properties' => [],
            ])
        );

        $content['application/json+ld'] = new Model\MediaType(
            new \ArrayObject([
                'type' => 'object',
                'properties' => [],
            ])
        );

        $requestBody = $requestBody->withContent($content);

        $post = $post->withRequestBody($requestBody);

        $responses = $post->getResponses();

        $responses['204'] = new Model\Response(
            description: 'Quest finished and checkpoint saved.',
            content: null
        );

        $post = $post->withResponses($responses);

        $pathItem = $pathItem->withPost($post);

        $paths->addPath('/api/adventure/quests/{id}/finish', $pathItem);

        return $openApi->withPaths($paths);
    }
}
