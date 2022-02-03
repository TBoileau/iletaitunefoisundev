<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\CreatePlayer;

use Symfony\Component\Validator\Constraints\NotBlank;

final class CreatePlayerInput
{
    #[NotBlank]
    public string $name;
}
