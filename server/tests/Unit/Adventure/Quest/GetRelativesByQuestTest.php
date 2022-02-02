<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Quest;

use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;
use App\Adventure\UseCase\Quest\GetRelativesByQuest\GetRelativesByQuest;
use App\Adventure\UseCase\Quest\GetRelativesByQuest\GetRelativesByQuestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetRelativesByQuestTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetRelativesByQuest(): void
    {
        $questId = new Ulid();

        $quest = new Quest();
        $quest->setId($questId);
        $quest->setName('Quest');
        $quest->setDifficulty(Difficulty::Easy);

        $questRelative1 = new Quest();
        $questRelative1->setId(new Ulid());
        $questRelative1->setName('Quest');
        $questRelative1->setDifficulty(Difficulty::Easy);

        $questRelative2 = new Quest();
        $questRelative2->setId(new Ulid());
        $questRelative2->setName('Quest');
        $questRelative2->setDifficulty(Difficulty::Easy);

        $questGateway = self::createMock(QuestGateway::class);
        $questGateway
            ->expects(self::once())
            ->method('getRelativesByQuest')
            ->with(self::equalTo((string) $questId))
            ->willReturn([$questRelative1, $questRelative2]);

        $handler = new GetRelativesByQuestHandler($questGateway);

        $query = new GetRelativesByQuest((string) $questId);

        $relatives = $handler($query);

        self::assertCount(2, $relatives);
    }
}
