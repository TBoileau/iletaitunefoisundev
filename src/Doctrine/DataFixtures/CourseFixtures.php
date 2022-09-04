<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use IncentiveFactory\Domain\Path\Level;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Course;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;

final class CourseFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [TrainingFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $trainings = $manager->getRepository(Training::class)->findAll();

        $index = 1;

        foreach ($trainings as $training) {
            for ($i = 1; $i <= 10; ++$i) {
                $manager->persist(
                    (new Course())
                        ->setTraining($training)
                        ->setName(sprintf('Course %d', $i))
                        ->setSlug(sprintf('course+%d', $index))
                        ->setContent(sprintf('Content %d', $i))
                        ->setExcerpt(sprintf('Excerpt %d', $i))
                        ->setImage('image.png')
                        ->setVideo('https://www.youtube.com/watch?v=rjtATsqc4C4')
                        ->setPublishedAt(new DateTimeImmutable())
                        ->setThread(['tweet 1', 'tweet 2'])
                        ->setLevel(Level::cases()[$i % 3])
                        ->setId($this->ulidGenerator->generate())
                );
                ++$index;
            }

            $manager->flush();
        }
    }
}
