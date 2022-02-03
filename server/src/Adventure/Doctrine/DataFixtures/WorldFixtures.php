<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\World;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class WorldFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $world = new World();
        $world->setName('Monde');
        $manager->persist($world);
        $manager->flush();
    }
}
