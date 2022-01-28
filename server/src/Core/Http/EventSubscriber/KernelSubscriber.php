<?php

declare(strict_types=1);

namespace App\Core\Http\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class KernelSubscriber implements EventSubscriberInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => ['onKernelException'],
            'kernel.view' => ['onKernelView'],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            /** @var array<array-key, array{propertyPath: string, message: string}> $errors */
            $errors = [];

            /** @var ConstraintViolation $violation */
            foreach ($exception->getViolations() as $violation) {
                $errors[] = [
                    'propertyPath' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            $event->setResponse(
                new JsonResponse(
                    [
                        'error' => [
                            'errors' => $errors,
                        ],
                        'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        'message' => $exception->getMessage(),
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                )
            );
        }
    }

    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        $status = match ($event->getRequest()->getMethod()) {
            Request::METHOD_POST => Response::HTTP_CREATED,
            Request::METHOD_GET => Response::HTTP_OK,
            default => Response::HTTP_NO_CONTENT,
        };

        $event->setResponse(
            new JsonResponse(
                $this->serializer->serialize($result, 'json', [ObjectNormalizer::GROUPS => ['Default', 'get']]),
                $status,
                ['Content-Type' => 'application/json'],
                true
            )
        );
    }
}
