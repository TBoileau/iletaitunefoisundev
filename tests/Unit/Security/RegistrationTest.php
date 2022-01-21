<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Command\RegistrationHandler;
use App\Security\Entity\User;
use App\Security\Gateway\UserGateway;
use App\Security\Message\Registration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

final class RegistrationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRegisterUser(): void
    {
        $registration = new Registration();
        $registration->setEmail('user+6@email.com');
        $registration->setPlainPassword('Password123!');

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

        $command = new RegistrationHandler($uuidGenerator, $userGateway, $userPasswordHasher);

        $command($registration);
    }
}
