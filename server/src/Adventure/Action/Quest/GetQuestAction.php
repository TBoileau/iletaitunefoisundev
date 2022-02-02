<?php

declare(strict_types=1);

namespace App\Adventure\Action\Quest;

use App\Adventure\Entity\Quest;
use App\Adventure\UseCase\Quest\GetQuest\GetQuest;
use App\Adventure\ViewModel\QuestViewModel;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/quests/{id}',
    name: 'get_quest',
    requirements: ['id' => '%routing.ulid%'],
    methods: [Request::METHOD_GET]
)]
final class GetQuestAction implements ActionInterface
{
    public function __invoke(QueryBusInterface $queryBus, string $id): QuestViewModel
    {
        /** @var Quest $quest */
        $quest = $queryBus->fetch(new GetQuest($id));

        return QuestViewModel::createFromQuest($quest);
    }
}
