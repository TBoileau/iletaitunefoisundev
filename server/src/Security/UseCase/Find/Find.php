<?php

declare(strict_types=1);

namespace App\Security\UseCase\Find;

use App\Core\Bus\Query\QueryInterface;
use App\Security\UseCase\Register\Register;

final class Find implements QueryInterface
{
    private function __construct(private string $email)
    {
    }

    public static function createFromRegister(Register $register): Find
    {
        return new self($register->getEmail());
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
