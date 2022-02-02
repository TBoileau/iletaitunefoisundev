<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Checkpoint;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\CheckpointGateway;
use App\Adventure\Gateway\QuestGateway;
use App\Adventure\UseCase\Checkpoint\PassCheckpoint\CheckpointPassed;
use App\Adventure\UseCase\Checkpoint\PassCheckpoint\PassCheckpoint;
use App\Adventure\UseCase\Checkpoint\PassCheckpoint\PassCheckpointHandler;
use App\Core\Bus\Event\EventBusInterface;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Uid\Ulid;

final class PassCheckpointTest extends TestCase
{
    /**
     * @test
     */
    public function shouldPassCheckpoint(): void
    {
        $ulid = Ulid::fromString('01FSY13PXFRJSR7FPBHZ5B2FNT');

        $quest = new Quest();

        $questGateway = self::createMock(QuestGateway::class);
        $questGateway
            ->expects(self::once())
            ->method('getQuestById')
            ->with(self: self::equalTo($ulid->__toString()))
            ->willReturn($quest);

        $checkpointGateway = self::createMock(CheckpointGateway::class);
        $checkpointGateway
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Checkpoint::class));

        $uuidGenerator = self::createMock(UlidGeneratorInterface::class);
        $uuidGenerator
            ->expects(self::once())
            ->method('generate')
            ->willReturn($ulid);

        $user = new User();
        $player = new Player();
        $journey = new Journey();
        $player->setJourney($journey);
        $user->setPlayer($player);

        $token = self::createMock(TokenInterface::class);
        $token
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $tokenStorage = self::createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $eventBus = self::createMock(EventBusInterface::class);
        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with(self::isInstanceOf(CheckpointPassed::class));

        $handler = new PassCheckpointHandler(
            $uuidGenerator,
            $checkpointGateway,
            $questGateway,
            $tokenStorage,
            $eventBus
        );

        $command = new PassCheckpoint();
        $command->setQuest((string) $ulid);

        $handler($command);
    }
}
