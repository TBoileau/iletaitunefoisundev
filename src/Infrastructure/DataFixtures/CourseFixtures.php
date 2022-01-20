<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Course\Entity\Course;
use App\Domain\Shared\Uuid\UuidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CourseFixtures extends Fixture
{
    public function __construct(private UuidGeneratorInterface $uuidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Course> $courses */
        $courses = [];

        for ($i = 1; $i <= 50; ++$i) {
            $course = $this->createCourse($i);
            $manager->persist($course);
            $courses[] = $course;
        }

        foreach ($courses as $i => $course) {
            if ($i < count($courses) - 1) {
                $course->addSibling($courses[$i + 1]);
            }
        }

        $manager->flush();
    }

    private function createCourse(int $index): Course
    {
        $course = new Course();
        $course->setId($this->uuidGenerator->generate());
        $course->setTitle(sprintf('Course %d', $index));
        $course->setSlug(sprintf('course-%d', $index));

        return $course;
    }
}
