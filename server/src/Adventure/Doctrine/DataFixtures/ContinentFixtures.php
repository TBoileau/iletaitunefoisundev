<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ContinentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, World> $worlds */
        $worlds = $manager->getRepository(World::class)->findAll();

        foreach ($worlds as $world) {
            for ($i = 1; $i <= 5; ++$i) {
                $continent = new Continent();
                $continent->setName(sprintf('Continent %d', $i));
                $continent->setWorld($world);
                $manager->persist($continent);
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [WorldFixtures::class];
    }
}
