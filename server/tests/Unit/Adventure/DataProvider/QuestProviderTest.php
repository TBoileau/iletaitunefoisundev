<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\DataProvider;

use App\Adventure\DataProvider\QuestProvider;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;
use PHPUnit\Framework\TestCase;

final class QuestProviderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnQuest(): void
    {
        $quest = new Quest();

        $questGateway = self::createMock(QuestGateway::class);
        $questGateway
            ->expects(self::once())
            ->method('getQuestById')
            ->with(self::equalTo(1))
            ->willReturn($quest);

        $provider = new QuestProvider($questGateway);

        self::assertTrue($provider->supports(Quest::class));
        self::assertEquals($quest, $provider->getItem(Quest::class, 1));
    }

    /**
     * @test
     */
    public function shouldReturnNull(): void
    {
        $questGateway = self::createMock(QuestGateway::class);
        $questGateway
            ->expects(self::never())
            ->method('getQuestById');

        $provider = new QuestProvider($questGateway);

        self::assertNull($provider->getItem(Quest::class, 'fail'));
    }
}
