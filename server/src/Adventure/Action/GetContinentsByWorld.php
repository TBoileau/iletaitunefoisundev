<?php

declare(strict_types=1);

namespace App\Adventure\Action;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Adventure\Message\RetrieveContinentsByWorld;
use App\Core\Http\Action\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/worlds/{id}/continents', name: 'get_continents_by_world', methods: [Request::METHOD_GET])]
final class GetContinentsByWorld extends AbstractAction
{
    /**
     * @return array<array-key, Continent>
     */
    public function __invoke(World $world): array
    {
        /** @var array<array-key, Continent> $continents */
        $continents = $this->handle(new RetrieveContinentsByWorld($world));

        return $continents;
    }
}
