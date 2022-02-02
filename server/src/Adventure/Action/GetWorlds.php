<?php

declare(strict_types=1);

namespace App\Adventure\Action;

use App\Adventure\Entity\World;
use App\Adventure\Message\RetrieveWorlds;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/worlds', name: 'get_worlds', methods: [Request::METHOD_GET])]
final class GetWorlds implements ActionInterface
{
    /**
     * @return array<array-key, World>
     */
    public function __invoke(): array
    {
        /** @var array<array-key, World> $worlds */
        $worlds = $this->handle(new RetrieveWorlds());

        return $worlds;
    }
}
