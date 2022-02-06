<?php

declare(strict_types=1);

namespace App\Adventure\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;

final class QuestProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @param QuestGateway<Quest> $questGateway
     */
    public function __construct(private QuestGateway $questGateway)
    {
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Quest
    {
        if (!is_int($id)) {
            return null;
        }

        return $this->questGateway->getQuestById($id);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Quest::class === $resourceClass;
    }
}
