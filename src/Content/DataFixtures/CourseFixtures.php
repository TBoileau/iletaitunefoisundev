<?php

declare(strict_types=1);

namespace App\Content\DataFixtures;

use App\Content\Entity\Course;
use App\Core\Uid\UlidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CourseFixtures extends Fixture
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 50; ++$i) {
            $manager->persist($this->createCourse($i));
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
