<?php

declare(strict_types=1);

namespace App\Security\Action;

use App\Security\Entity\User;
use App\Security\Message\Registration;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OpenApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[OpenApi\RequestBody(
    description: 'Send user\'s information like email and plain password.',
    content: new OpenApi\JsonContent(ref: new Nelmio\Model(type: Registration::class)),
)]
#[OpenApi\Response(
    response: Response::HTTP_CREATED,
    description: 'Returns the newly registered user.',
    content: new OpenApi\JsonContent(ref: new Nelmio\Model(type: User::class, groups: ['get'])),
)]
#[OpenApi\Response(
    response: Response::HTTP_UNPROCESSABLE_ENTITY,
    description: 'Returns violation constraint list.',
    content: new OpenApi\JsonContent(
        type: 'object',
        properties: [
            new OpenApi\Property(
                property: 'error',
                type: 'object',
                properties: [
                    new OpenApi\Property(
                        property: 'errors',
                        type: 'array',
                        items: new OpenApi\Items(
                            type: 'object',
                            properties: [
                                new OpenApi\Property(type: 'string', property: 'propertyPath'),
                                new OpenApi\Property(type: 'string', property: 'message'),
                            ]
                        )
                    ),
                ]
            ),
            new OpenApi\Property(property: 'code', type: 'integer'),
            new OpenApi\Property(property: 'message', type: 'string'),
        ]
    ),
)]
#[OpenApi\Tag('Security')]
#[Route('/register', name: 'register', methods: [Request::METHOD_POST])]
#[ParamConverter('registration')]
final class Register extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Registration $registration): JsonResponse
    {
        /** @var User $user */
        $user = $this->handle($registration);

        return $this->json(
            $user,
            Response::HTTP_CREATED,
            [],
            [ObjectNormalizer::GROUPS => ['get']]
        );
    }
}
