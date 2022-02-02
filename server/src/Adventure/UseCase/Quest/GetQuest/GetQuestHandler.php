<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Quest\GetQuest;

use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetQuestHandler implements QueryHandlerInterface
{
    /**
     * @param QuestGateway<Quest> $questGateway
     */
    public function __construct(private QuestGateway $questGateway)
    {
    }

    public function __invoke(GetQuest $getQuest): Quest
    {
        return $this->questGateway->getQuestById($getQuest->getId());
    }
}
