<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Adventure\UseCase\SaveCheckpoint\SaveCheckpointHandler;
use App\Adventure\UseCase\SaveCheckpoint\SaveCheckpointInput;
use PHPUnit\Framework\TestCase;

final class SaveCheckpointTest extends TestCase
{
    /**
     * @test
     */
    public function shouldPassCheckpoint(): void
    {
        $checkpointGateway = self::createMock(CheckpointGateway::class);
        $checkpointGateway
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Checkpoint::class));

        $handler = new SaveCheckpointHandler($checkpointGateway);

        $command = new SaveCheckpointInput();
        $command->quest = new Quest();
        $command->journey = new Journey();

        $handler($command);
    }
}
