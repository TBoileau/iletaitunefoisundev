<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\Type;
use App\Content\Doctrine\DataFixtures\CourseFixtures;
use App\Content\Doctrine\DataFixtures\QuizFixtures;
use App\Content\Entity\Course;
use App\Content\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Laudis\Neo4j\Contracts\ClientInterface;

final class QuestFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private ClientInterface $neo4jClient)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Region> $regions */
        $regions = $manager->getRepository(Region::class)->findAll();

        /** @var array<array-key, Course> $courses */
        $courses = $manager->getRepository(Course::class)->findAll();

        /** @var array<array-key, Quiz> $quizzes */
        $quizzes = $manager->getRepository(Quiz::class)->findAll();

        $nodeIndex = 0;

        foreach ($regions as $region) {
            /** @var array<int, array{id: ?int, type: Type, next: ?int, relatives: array<array-key, int>}> $quests */
            $quests = [
                1 => [
                    'type' => Type::Main,
                    'next' => 2,
                    'relatives' => [4],
                ],
                2 => [
                    'type' => Type::Main,
                    'next' => 3,
                    'relatives' => [5],
                ],
                3 => [
                    'type' => Type::Main,
                    'next' => null,
                    'relatives' => [4],
                ],
                4 => [
                    'type' => Type::Side,
                    'next' => null,
                    'relatives' => [5],
                ],
                5 => [
                    'type' => Type::Side,
                    'relatives' => [],
                ],
            ];

            foreach ($quests as $i => &$questInfo) {
                $quest = new Quest();
                $quest->setName(sprintf('Quest %d', $i));
                $quest->setRegion($region);
                $quest->setCourse($courses[$nodeIndex]);
                $quest->setQuiz($quizzes[$nodeIndex]);
                $quest->setDifficulty(match ($i) {
                    1, 2 => Difficulty::Easy,
                    3, 4 => Difficulty::Normal,
                    default => Difficulty::Hard,
                });
                $quest->setType($questInfo['type']);
                $manager->persist($quest);
                if (1 === $i) {
                    $region->setFirstQuest($quest);
                }
                $manager->flush();
                $questInfo['id'] = $quest->getId();
                $this->neo4jClient->run('CREATE(q:Quest {id: $id});', ['id' => $quest->getId()]);
                ++$nodeIndex;
            }

            foreach ($quests as $quest) {
                if (null !== $quest['next']) {
                    $this->neo4jClient->run(
                        'MATCH (q1:Quest), (q2:Quest) WHERE q1.id = $q1 AND q2.id = $q2 MERGE (q1)-[:NEXT]->(q2);',
                        ['q1' => $quest['id'], 'q2' => $quests[$quest['next']]['id']]
                    );
                }

                foreach ($quest['relatives'] as $relative) {
                    $this->neo4jClient->run(
                        'MATCH (q1:Quest), (q2:Quest) WHERE q1.id = $q1 AND q2.id = $q2 MERGE (q1)-[:RELATIVE]->(q2);',
                        ['q1' => $quest['id'], 'q2' => $quests[$relative]['id']]
                    );
                }
            }
        }
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [RegionFixtures::class, CourseFixtures::class, QuizFixtures::class];
    }
}
