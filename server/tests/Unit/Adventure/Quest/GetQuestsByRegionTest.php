<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Quest;

use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Gateway\QuestGateway;
use App\Adventure\UseCase\Quest\GetQuestsByRegion\GetQuestsByRegion;
use App\Adventure\UseCase\Quest\GetQuestsByRegion\GetQuestsByRegionHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetQuestsByRegionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetQuestsByRegion(): void
    {
        $regionId = new Ulid();

        $region = new Region();
        $region->setId($regionId);
        $region->setName('Monde');

        $retrieveQuestsByRegion = new GetQuestsByRegion((string) $regionId);

        $quest = new Quest();
        $quest->setRegion($region);
        $quest->setId(new Ulid());
        $quest->setName('Quest');

        $questGateway = self::createMock(QuestGateway::class);
        $questGateway
            ->expects(self::once())
            ->method('getQuestsByRegion')
            ->with(self::equalTo((string) $regionId))
            ->willReturn([$quest]);

        $handler = new GetQuestsByRegionHandler($questGateway);

        /** @var array<int, Quest> $quests */
        $quests = $handler($retrieveQuestsByRegion);

        self::assertCount(1, $quests);
        self::assertEquals('Quest', $quests[0]->getName());
        self::assertEquals($region, $quests[0]->getRegion());
    }
}
