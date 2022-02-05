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

        $this->addQuestPath($paths, 'start', 'Start a quest.');
        $this->addQuestPath($paths, 'finish', 'Finish a quest.');

        return $openApi->withPaths($paths);
    }

    private function addQuestPath(Model\Paths $paths, string $action, string $description): void
    {
        $iri = sprintf('/api/adventure/quests/{id}/%s', $action);

        /** @var Model\PathItem $pathItem */
        $pathItem = $paths->getPath($iri);

        /** @var Model\Operation $post */
        $post = $pathItem->getPost();

        $post = $post->withSummary($description);

        $post = $post->withDescription($description);

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
            description: 'No content',
            content: null
        );

        $post = $post->withResponses($responses);

        $paths->addPath($iri, $pathItem->withPost($post));
    }
}
