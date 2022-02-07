<?php

declare(strict_types=1);

namespace App\Security\UseCase\RequestForgottenPassword;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use App\Security\Factory\UuidV6FactoryInterface;
use App\Security\Mail\RequestForgottenPasswordMail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RequestForgottenPasswordHandler implements MessageHandlerInterface
{
    /**
     * @param UserGateway<User> $userGateway
     */
    public function __construct(
        private UserGateway $userGateway,
        private UuidV6FactoryInterface $uuidFactory,
        private MailerInterface $mailer
    ) {
    }

    public function __invoke(RequestForgottenPasswordInput $requestForgottenPasswordInput): void
    {
        /** @var ?User $user */
        $user = $this->userGateway->getUserByIdentifier($requestForgottenPasswordInput->email);
        if (null === $user) {
            return;
        }

        $user->setForgottenPasswordToken($this->uuidFactory->create()->toRfc4122());
        $this->userGateway->update($user);

        $this->mailer->send(new RequestForgottenPasswordMail($user));
    }
}
