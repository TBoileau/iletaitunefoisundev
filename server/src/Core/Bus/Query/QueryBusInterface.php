<?php

declare(strict_types=1);

namespace App\Core\Bus\Query;

interface QueryBusInterface
{
    public function fetch(QueryInterface $query): mixed;
}