<?php

declare(strict_types=1);

namespace App\Security\Action;

use App\Core\Http\Action\AbstractAction;
use App\Security\Entity\User;
use App\Security\Message\Registration;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OpenApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OpenApi\RequestBody(
    description: 'Send user\'s information like email and plain password.',
    content: new OpenApi\JsonContent(ref: new Nelmio\Model(type: Registration::class)),
)]
#[OpenApi\Response(
    response: Response::HTTP_CREATED,
    description: 'Returns the newly registered user.',
    content: new OpenApi\JsonContent(ref: new Nelmio\Model(type: User::class, groups: ['Default', 'get'])),
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
final class Register extends AbstractAction
{
    public function __invoke(Registration $registration): User
    {
        /** @var User $user */
        $user = $this->handle($registration);

        return $user;
    }
}
