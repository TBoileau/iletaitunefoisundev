<?php

declare(strict_types=1);

namespace App\Security\Action;

use App\Security\Entity\User;
use App\Security\Message\Registration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/register', name: 'register', methods: [Request::METHOD_POST])]
final class Register extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $registration = $serializer->deserialize($request->getContent(), Registration::class, 'json');

        $constraintViolationList = $validator->validate($registration);

        if ($constraintViolationList->count() > 0) {
            throw new ValidationFailedException($registration, $constraintViolationList);
        }

        /** @var User $user */
        $user = $this->handle($registration);

        return $this->json($user, Response::HTTP_CREATED);
    }
}
