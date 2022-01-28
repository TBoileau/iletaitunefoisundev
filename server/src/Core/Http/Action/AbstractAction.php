<?php

declare(strict_types=1);

namespace App\Core\Http\Action;

use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractAction
{
    private MessageBusInterface $messageBus;

    #[Required]
    public function setMessageBus(MessageBusInterface $messageBus): void
    {
        $this->messageBus = $messageBus;
    }

    protected function handle(object $message): mixed
    {
        if (!isset($this->messageBus)) {
            throw new LogicException(sprintf('You must provide a "%s" instance in the "%s::$messageBus" property, but that property has not been initialized yet.', MessageBusInterface::class, static::class));
        }

        $envelope = $this->messageBus->dispatch($message);
        /** @var HandledStamp[] $handledStamps */
        $handledStamps = $envelope->all(HandledStamp::class);

        if (1 !== count($handledStamps)) {
            throw new LogicException(sprintf('Message of type "%s" was handled zero times. Exactly one handler is expected when using "%s::%s()".', get_debug_type($envelope->getMessage()), static::class, __FUNCTION__));
        }

        if (\count($handledStamps) > 1) {
            $handlers = implode(', ', array_map(function (HandledStamp $stamp): string {
                return sprintf('"%s"', $stamp->getHandlerName());
            }, $handledStamps));

            throw new LogicException(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected when using "%s::%s()", got %d: %s.', get_debug_type($envelope->getMessage()), static::class, __FUNCTION__, \count($handledStamps), $handlers));
        }

        return $handledStamps[0]->getResult();
    }
}
