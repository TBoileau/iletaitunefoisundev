<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\CreatePlayer;

use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Adventure\Gateway\PlayerGateway;
use App\Security\Entity\User;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

final class CreatePlayerHandler implements MessageHandlerInterface
{
    /**
     * @param PlayerGateway<Player> $playerGateway
     */
    public function __construct(private PlayerGateway $playerGateway, private Security $security)
    {
    }

    public function __invoke(CreatePlayerInput $createPlayer): Player
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $player = new Player();
        $player->setJourney(new Journey());
        $player->setName($createPlayer->name);
        $player->setUser($user);

        $this->playerGateway->create($player);

        return $player;
    }
}
