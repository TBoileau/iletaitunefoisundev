<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Adventure\UseCase\Quest\StartQuest\StartQuestHandler;
use App\Adventure\UseCase\Quest\StartQuest\StartQuestInput;
use PHPUnit\Framework\TestCase;

final class StartQuestTest extends TestCase
{
    /**
     * @test
     */
    public function shouldStartQuest(): void
    {
        $quest = new Quest();

        $player = new Player();
        $player->setJourney(new Journey());

        $checkpointGateway = self::createMock(CheckpointGateway::class);
        $checkpointGateway
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Checkpoint::class));

        $handler = new StartQuestHandler($checkpointGateway);

        $handler(StartQuestInput::create($player, $quest));
    }
}
