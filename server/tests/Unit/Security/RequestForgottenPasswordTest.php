<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use App\Security\Factory\UuidV6Factory;
use App\Security\Mail\RequestForgottenPasswordMail;
use App\Security\UseCase\RequestForgottenPassword\RequestForgottenPasswordHandler;
use App\Security\UseCase\RequestForgottenPassword\RequestForgottenPasswordInput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Uid\Uuid;

final class RequestForgottenPasswordTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDefineANewForgottenPasswordToken(): void
    {
        $testEmail = 'user+1@email.com';
        $requestForgottenPasswordInput = new RequestForgottenPasswordInput();
        $requestForgottenPasswordInput->email = $testEmail;

        $user = new User();
        $user->setEmail($testEmail);

        $userGateway = self::createMock(UserGateway::class);
        $userGateway
            ->expects(self::once())
            ->method('update')
            ->with(self::equalTo($user));

        $userGateway
            ->expects(self::once())
            ->method('getUserByIdentifier')
            ->with(self::equalTo($testEmail))
            ->willReturn($user);

        $uuid = Uuid::v6();
        $uuidFactory = self::createMock(UuidV6Factory::class);
        $uuidFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($uuid);

        $user->setForgottenPasswordToken($uuid->toRfc4122());

        $mailer = self::createMock(MailerInterface::class);
        $mailer
            ->expects(self::once())
            ->method('send')
            ->with(self::equalTo(new RequestForgottenPasswordMail($user)));

        $commandHandler = new RequestForgottenPasswordHandler($userGateway, $uuidFactory, $mailer);

        $commandHandler($requestForgottenPasswordInput);
    }
}
