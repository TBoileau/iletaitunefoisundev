<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Doctrine\DataFixtures\UserFixtures;
use App\Security\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, User> $users */
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $i => $user) {
            $player = new Player();
            $player->setId($this->ulidGenerator->generate());
            $player->setName(sprintf('Player %d', $i + 1));
            $player->setUser($user);

            $journey = new Journey();
            $journey->setId($this->ulidGenerator->generate());
            $player->setJourney($journey);

            $manager->persist($player);
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
