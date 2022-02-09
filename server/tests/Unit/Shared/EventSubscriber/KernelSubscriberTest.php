<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\EventSubscriber;

use App\Shared\EventSubscriber\KernelSubscriber;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

final class KernelSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnSubscribedEvents(): void
    {
        self::assertEquals(
            ['kernel.exception' => ['onKernelException']],
            KernelSubscriber::getSubscribedEvents()
        );
    }

    /**
     * @test
     */
    public function shouldSerializeException(): void
    {
        $serializer = self::createMock(SerializerInterface::class);

        $kernelSubscriber = new KernelSubscriber($serializer);

        $kernel = self::createMock(HttpKernelInterface::class);

        $request = new Request();

        $object = new stdClass();

        $constraintViolationList = new ConstraintViolationList();

        $exception = new ValidationFailedException($object, $constraintViolationList);

        $event = new ExceptionEvent($kernel, $request, 1, $exception);

        $kernelSubscriber->onKernelException($event);
        
        self::assertNotNull($event->getResponse());
        self::assertSame(Response::HTTP_BAD_REQUEST, $event->getResponse()->getStatusCode());
    }
}
