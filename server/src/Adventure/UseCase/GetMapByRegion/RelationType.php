<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\GetMapByRegion;

enum RelationType: string
{
    case Relative = 'RELATIVE';
    
    case Next = 'NEXT';
}
