<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use App\Security\UseCase\ResetForgottenPassword\ResetForgottenPasswordHandler;
use App\Security\UseCase\ResetForgottenPassword\ResetForgottenPasswordInput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetForgottenPasswordTest extends TestCase
{
    /**
     * @test
     */
    public function shouldResetUserPassword(): void
    {
        $testEmail = 'user+1@email.com';
        $forgottenPasswordToken = 'test-password-forgotten-token';
        $plainPassword = 'new-password-test';
        $resetForgottenPasswordInput = new ResetForgottenPasswordInput();
        $resetForgottenPasswordInput->plainPassword = $plainPassword;
        $resetForgottenPasswordInput->forgottenPasswordToken = $forgottenPasswordToken;

        $user = new User();
        $user->setEmail($testEmail);
        $user->setForgottenPasswordToken($forgottenPasswordToken);

        $userGateway = self::createMock(UserGateway::class);
        $userGateway
            ->expects(self::once())
            ->method('update')
            ->with(self::equalTo($user));

        $userGateway
            ->expects(self::once())
            ->method('getUserByForgottenPasswordToken')
            ->with(self::equalTo($forgottenPasswordToken))
            ->willReturn($user);

        $userPasswordHasher = self::createMock(UserPasswordHasherInterface::class);
        $userPasswordHasher
            ->expects(self::once())
            ->method('hashPassword')
            ->with($user, $plainPassword);

        $commandHandler = new ResetForgottenPasswordHandler($userGateway, $userPasswordHasher);

        $commandHandler($resetForgottenPasswordInput);
    }
}
