<?php

declare(strict_types=1);

namespace App\Core\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class KernelSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => ['onKernelException'],
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
}
