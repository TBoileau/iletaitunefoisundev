<?php

declare(strict_types=1);

namespace App\Node\DataFixtures;

use App\Core\Uid\UlidGeneratorInterface;
use App\Node\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CourseFixtures extends Fixture
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
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
                $course->getSiblings()->add($courses[$i + 1]);
            }
        }

        $manager->flush();
    }

    private function createCourse(int $index): Course
    {
        $course = new Course();
        $course->setId($this->ulidGenerator->generate());
        $course->setTitle(sprintf('Course %d', $index));
        $course->setSlug(sprintf('course-%d', $index));
        $course->setYoutubeId('-S94RNjjb4I');
        $course->setDescription(sprintf('Description %s', $index));

        return $course;
    }
}
