<?php

declare(strict_types=1);

namespace App\Adventure\Controller;

use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\UseCase\Quest\StartQuest\StartQuestInput;
use App\Security\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

final class StartQuestController
{
    public function __construct(private MessageBusInterface $messageBus, private Security $security)
    {
    }

    public function __invoke(Quest $quest): JsonResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();

        /** @var Player $player */
        $player = $user->getPlayer();

        $this->messageBus->dispatch(StartQuestInput::create($player, $quest));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
