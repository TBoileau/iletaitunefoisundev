<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Course\Entity\Course;
use App\Domain\Node\Entity\Node;
use App\Domain\Node\Entity\Step;
use App\Domain\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ProgressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, User> $users */
        $users = $manager->getRepository(User::class)->findAll();

        /** @var Course $course */
        $course = $manager->getRepository(Course::class)->findOneBy(['slug' => 'course-1']);

        foreach ($users as $user) {
            $startedAt = new DateTimeImmutable('2022-01-01 00:00:00');

            for ($i = 1; $i <= 25; ++$i) {
                $step = $this->createStep($user, $course, $startedAt, $i < 25);
                $manager->persist($step);

                /** @var Course $course */
                $course = $course->getSiblings()->first();

                if (null !== $step->getFinishedAt()) {
                    $startedAt = $step->getFinishedAt()->add(new \DateInterval('PT1M'));
                }
            }

            $manager->flush();
        }
    }

    private function createStep(
        User $user,
        Node $node,
        DateTimeImmutable $startedAt,
        bool $finished = true
    ): Step {
        $step = new Step();
        $step->setUser($user);
        $step->setNode($node);
        $step->setStartedAt($startedAt);

        if ($finished) {
            $step->setFinishedAt($step->getStartedAt()->add(new \DateInterval('PT59M')));
            $step->setGrade(rand(1, 5));
            $step->setComment('Commentaire');
        }

        return $step;
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class, CourseFixtures::class];
    }
}
