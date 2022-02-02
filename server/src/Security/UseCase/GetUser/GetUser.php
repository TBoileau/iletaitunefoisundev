<?php

declare(strict_types=1);

namespace App\Security\UseCase\GetUser;

use App\Core\Bus\Query\QueryInterface;
use App\Security\UseCase\Register\Register;

final class GetUser implements QueryInterface
{
    private function __construct(private string $email)
    {
    }

    public static function createFromRegister(Register $register): GetUser
    {
        return new self($register->getEmail());
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
