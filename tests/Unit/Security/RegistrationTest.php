<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Domain\Security\Command\RegistrationHandler;
use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Domain\Security\Message\Registration;
use App\Domain\Shared\Uuid\UuidGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

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

        $uuid = Uuid::fromString('a8cf3ac5-4370-4981-bada-b57eec49ebbb');

        $uuidGenerator = self::createMock(UuidGeneratorInterface::class);
        $uuidGenerator
            ->expects(self::once())
            ->method('generate')
            ->willReturn($uuid);

        $user = new User();
        $user->setId($uuid);
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
