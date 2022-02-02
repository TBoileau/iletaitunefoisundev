<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Core\Bus\Event\EventBusInterface;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Contract\Gateway\UserGateway;
use App\Security\Entity\User;
use App\Security\UseCase\GetUserByEmail\GetUserByEmail;
use App\Security\UseCase\GetUserByEmail\GetUserByEmailHandler;
use App\Security\UseCase\Register\Register;
use App\Security\UseCase\Register\Registered;
use App\Security\UseCase\Register\RegisterHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

final class RegisterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRegisterAndFindUser(): void
    {
        $register = new Register();
        $register->setEmail('user+6@email.com');
        $register->setPlainPassword('Password123!');

        $ulid = Ulid::fromString('01FSY13PXFRJSR7FPBHZ5B2FNT');

        $uuidGenerator = self::createMock(UlidGeneratorInterface::class);
        $uuidGenerator
            ->expects(self::once())
            ->method('generate')
            ->willReturn($ulid);

        $user = new User();
        $user->setId($ulid);
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
        $userGateway
            ->expects(self::once())
            ->method('findUserByEmail')
            ->with(self::equalTo('user+6@email.com'))
            ->willReturn($user);

        $eventBus = self::createMock(EventBusInterface::class);
        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with(self::isInstanceOf(Registered::class));

        $commandHandler = new RegisterHandler(
            $uuidGenerator,
            $userGateway,
            $eventBus,
            $userPasswordHasher
        );

        $commandHandler($register);

        $queryHandler = new GetUserByEmailHandler($userGateway);

        $queryHandler(GetUserByEmail::createFromRegister($register));
    }
}
