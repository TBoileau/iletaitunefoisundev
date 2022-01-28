<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

enum Difficulty: int
{
    case Easy = 1;
    case Normal = 2;
    case Hard = 3;
}
