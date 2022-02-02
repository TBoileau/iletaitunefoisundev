<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\World;
use App\Core\Uid\UlidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class WorldFixtures extends Fixture
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $world = new World();
        $world->setId($this->ulidGenerator->generate());
        $world->setName('Monde');
        $manager->persist($world);
        $manager->flush();
    }
}
