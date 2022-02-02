<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Quest;

use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;
use App\Adventure\UseCase\Quest\GetQuest\GetQuest;
use App\Adventure\UseCase\Quest\GetQuest\GetQuestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetQuestTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetQuest(): void
    {
        $questId = new Ulid();

        $quest = new Quest();
        $quest->setId($questId);
        $quest->setName('Quest');

        $questGateway = self::createMock(QuestGateway::class);
        $questGateway
            ->expects(self::once())
            ->method('getQuestById')
            ->with(self::equalTo((string) $questId))
            ->willReturn($quest);

        $handler = new GetQuestHandler($questGateway);

        $getQuest = new GetQuest((string) $questId);

        $quest = $handler($getQuest);

        self::assertEquals('Quest', $quest->getName());
    }
}
