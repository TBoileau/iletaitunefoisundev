<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Quest\GetQuestsByRegion;

use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetQuestsByRegionHandler implements QueryHandlerInterface
{
    /**
     * @param QuestGateway<Quest> $questGateway
     */
    public function __construct(private QuestGateway $questGateway)
    {
    }

    /**
     * @return array<array-key, Quest>
     */
    public function __invoke(GetQuestsByRegion $findQuestsByWorld): array
    {
        return $this->questGateway->getQuestsByRegion($findQuestsByWorld->getId());
    }
}
