<?php

declare(strict_types=1);

namespace App\Adventure\Action\Quest;

use App\Adventure\Entity\Quest;
use App\Adventure\UseCase\Quest\GetRelativesByQuest\GetRelativesByQuest;
use App\Adventure\ViewModel\QuestsViewModel;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/quests/{id}/relatives',
    name: 'get_relatives_by_quest',
    requirements: ['id' => '%routing.ulid%'],
    methods: [Request::METHOD_GET]
)]
final class GetRelativesByQuestAction implements ActionInterface
{
    public function __invoke(QueryBusInterface $queryBus, string $id): QuestsViewModel
    {
        /** @var array<array-key, Quest> $quests */
        $quests = $queryBus->fetch(new GetRelativesByQuest($id));

        return QuestsViewModel::createFromQuests($quests);
    }
}
