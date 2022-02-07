<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use App\Security\UseCase\ResetForgottenPassword\ResetForgottenPasswordHandler;
use App\Security\UseCase\ResetForgottenPassword\ResetForgottenPasswordInput;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetForgottenPasswordTest extends TestCase
{
    /**
     * @test
     */
    public function shouldResetUserPassword(): void
    {
        $testEmail = 'user+1@email.com';
        $forgottenPasswordToken = 'test-password-forgottent-token';
        $plainPassword = 'new-password-test';
        $resetForgottenPasswordInput = new ResetForgottenPasswordInput();
        $resetForgottenPasswordInput->email = $testEmail;
        $resetForgottenPasswordInput->plainPassword = $plainPassword;
        $resetForgottenPasswordInput->forgottenPasswordToken = $forgottenPasswordToken;

        $user = new User();
        $user->setEmail($testEmail);
        $user->setForgottenPasswordToken($forgottenPasswordToken);

        $userLoader = self::createMock(UserLoaderInterface::class);
        $userLoader
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with(self::equalTo($testEmail))
            ->willReturn($user)
        ;

        $userGateway = self::createMock(UserGateway::class);
        $userGateway
            ->expects(self::once())
            ->method('update')
            ->with(self::equalTo($user));

        $userPasswordHasher = self::createMock(UserPasswordHasherInterface::class);
        $userPasswordHasher
            ->expects(self::once())
            ->method('hashPassword')
            ->with($user, $plainPassword);

        $commandHandler = new ResetForgottenPasswordHandler($userGateway, $userLoader, $userPasswordHasher);

        $commandHandler($resetForgottenPasswordInput);
    }
}
