<?php

declare(strict_types=1);

namespace App\Adventure\Action\Checkpoint;

use App\Adventure\UseCase\Checkpoint\PassCheckpoint\PassCheckpoint;
use App\Core\Bus\Command\CommandBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/checkpoints',
    name: 'pass_checkpoint',
    methods: [Request::METHOD_POST]
)]
final class PassCheckpointAction implements ActionInterface
{
    public function __invoke(CommandBusInterface $commandBus, PassCheckpoint $passCheckpoint): void
    {
        $commandBus->dispatch($passCheckpoint);
    }
}
