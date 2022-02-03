<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure;

use App\Adventure\Entity\Player;
use App\Adventure\Gateway\PlayerGateway;
use App\Adventure\UseCase\CreatePlayer\CreatePlayerHandler;
use App\Adventure\UseCase\CreatePlayer\CreatePlayerInput;
use App\Security\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

final class CreatePlayerTest extends TestCase
{
    public function shouldCreatePlayer(): void
    {
        $createPlayer = new CreatePlayerInput();
        $createPlayer->name = 'Joueur 0';

        $playerGateway = self::createMock(PlayerGateway::class);
        $playerGateway
            ->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(Player::class));

        $security = self::createMock(Security::class);
        $security
            ->expects(self::once())
            ->method('getUser')
            ->willReturn(new User());

        $handler = new CreatePlayerHandler($playerGateway, $security);

        $handler($createPlayer);
    }
}
