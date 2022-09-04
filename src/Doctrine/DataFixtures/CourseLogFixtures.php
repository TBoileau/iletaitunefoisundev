<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Course;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\CourseLog;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path;

final class CourseLogFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [PathFixtures::class, CourseFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $paths = $manager->getRepository(Path::class)->findAll();

        foreach ($paths as $path) {
            /** @var Course $course */
            $course = $manager->getRepository(Course::class)->findOneBy(['training' => $path->getTraining()]);
            $manager->persist(
                (new CourseLog())
                    ->setPath($path)
                    ->setCourse($course)
                    ->setBeganAt(new DateTimeImmutable())
                    ->setId($this->ulidGenerator->generate())
            );

            $manager->flush();
        }
    }
}
