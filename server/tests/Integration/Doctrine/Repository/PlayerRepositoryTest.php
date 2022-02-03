<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\PlayerRepository;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Security\Doctrine\Repository\UserRepository;
use App\Security\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PlayerRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function shouldCreatePlayer(): void
    {
        self::bootKernel();

        /** @var UserRepository<User> $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = new User();
        $user->setEmail('user+7@email.com');
        $user->setPassword('password');

        $userRepository->register($user);

        /** @var PlayerRepository<Player> $playerRepository */
        $playerRepository = self::getContainer()->get(PlayerRepository::class);

        $player = new Player();
        $player->setName('Joueur 0');
        $player->setJourney(new Journey());
        $player->setUser($user);

        $playerRepository->create($player);

        $player = $playerRepository->find($player->getId());

        self::assertNotNull($player);
        self::assertEquals('Joueur 0', $player->getName());
        self::assertEquals($user, $player->getUser());
        self::assertNotNull($user->getPlayer());
    }
}
