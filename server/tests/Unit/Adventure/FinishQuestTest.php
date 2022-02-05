<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Adventure\UseCase\Quest\FinishQuest\FinishQuestHandler;
use App\Adventure\UseCase\Quest\FinishQuest\FinishQuestInput;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class FinishQuestTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFinishQuest(): void
    {
        $quest = new Quest();

        $player = new Player();
        $player->setJourney(new Journey());

        $checkpoint = new Checkpoint();
        $checkpoint->setJourney($player->getJourney());
        $checkpoint->setStartedAt(new DateTimeImmutable());
        $checkpoint->setQuest($quest);

        $checkpointGateway = self::createMock(CheckpointGateway::class);
        $checkpointGateway
            ->expects(self::once())
            ->method('getCheckpointByPlayerAndQuest')
            ->with(self::equalTo($player), self::equalTo($quest))
            ->willReturn($checkpoint);
        $checkpointGateway
            ->expects(self::once())
            ->method('save')
            ->with(self::equalTo($checkpoint));

        $checkpointGateway
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Checkpoint::class));

        $handler = new FinishQuestHandler($checkpointGateway);

        self::assertNull($checkpoint->getFinishedAt());

        $handler(FinishQuestInput::create($player, $quest));

        self::assertNotNull($checkpoint->getFinishedAt());
    }
}
