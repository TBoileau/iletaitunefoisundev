<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\EventListener;

use IncentiveFactory\Domain\Player\Register\NewRegistration;
use IncentiveFactory\Domain\Shared\Event\EventListener;
use IncentiveFactory\IlEtaitUneFoisUnDev\Mailer\ValidRegistrationEmail;
use Symfony\Component\Mailer\MailerInterface;

final class PlayerRegisteredListener implements EventListener
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(NewRegistration $newRegistration): void
    {
        $this->mailer->send(new ValidRegistrationEmail($newRegistration->player));
    }
}
