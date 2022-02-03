<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use App\Security\UseCase\Register\RegisterHandler;
use App\Security\UseCase\Register\RegisterInput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRegisterAndFindUser(): void
    {
        $register = new RegisterInput();
        $register->email = 'user+6@email.com';
        $register->plainPassword = 'Password123!';

        $user = new User();
        $user->setEmail('user+6@email.com');
        $user->setPassword('hashed_password');

        $userPasswordHasher = self::createMock(UserPasswordHasherInterface::class);
        $userPasswordHasher
            ->expects(self::once())
            ->method('hashPassword')
            ->with(self::isInstanceOf(User::class), self::equalTo('Password123!'))
            ->willReturn('hashed_password');

        $userGateway = self::createMock(UserGateway::class);
        $userGateway
            ->expects(self::once())
            ->method('register')
            ->with(self::equalTo($user));

        $commandHandler = new RegisterHandler($userGateway, $userPasswordHasher);

        $output = $commandHandler($register);

        self::assertEquals('user+6@email.com', $output->getEmail());
    }
}
