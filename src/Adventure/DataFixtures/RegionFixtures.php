<?php

declare(strict_types=1);

namespace App\Adventure\DataFixtures;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Region;
use App\Core\Uid\UlidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class RegionFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Continent> $continents */
        $continents = $manager->getRepository(Continent::class)->findAll();

        foreach ($continents as $continent) {
            for ($i = 1; $i <= 5; ++$i) {
                $region = new Region();
                $region->setId($this->ulidGenerator->generate());
                $region->setName(sprintf('Region %d', $i));
                $region->setContinent($continent);
                $manager->persist($region);
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [ContinentFixtures::class];
    }
}
