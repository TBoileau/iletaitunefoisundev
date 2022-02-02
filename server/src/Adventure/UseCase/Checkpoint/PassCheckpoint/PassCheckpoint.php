<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Checkpoint\PassCheckpoint;

use App\Core\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PassCheckpoint implements CommandInterface
{
    #[NotBlank]
    private string $quest;

    public function setQuest(string $quest): void
    {
        $this->quest = $quest;
    }

    public function getQuest(): string
    {
        return $this->quest;
    }
}
