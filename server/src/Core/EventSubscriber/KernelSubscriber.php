<?php

declare(strict_types=1);

namespace App\Core\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Serializer\SerializerInterface;

final class KernelSubscriber implements EventSubscriberInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['kernel.exception' => ['onKernelException']];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            $event->setResponse(new Response(
                $this->serializer->serialize(
                    $exception->getViolations(),
                    'json'
                ),
                Response::HTTP_BAD_REQUEST,
            ));
        }
    }
}
