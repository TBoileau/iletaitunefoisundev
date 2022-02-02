<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Continent\GetContinentsByWorld;

use App\Core\Bus\Query\QueryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class GetContinentsByWorld implements QueryInterface
{
    #[NotBlank]
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
