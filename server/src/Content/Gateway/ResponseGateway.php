<?php

declare(strict_types=1);

namespace App\Content\Gateway;

use App\Content\Entity\Quiz\Response;

/**
 * @template T
 */
interface ResponseGateway
{
    public function submit(Response $response): void;

    public function getResponseById(int $id): ?Response;
}
