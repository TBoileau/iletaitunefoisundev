<?php

declare(strict_types=1);

namespace App\Content\Entity;

enum Format: string
{
    case Multiple = 'multiple';
    case Unique = 'unique';
}
