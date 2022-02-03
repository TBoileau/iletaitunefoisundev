<?php

declare(strict_types=1);

namespace App\Content\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class CourseFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('youtube', [$this, 'youtube'])];
    }

    public function youtube(string $url): string
    {
        preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=(.+)$/', $url, $matches);

        return $matches[1];
    }
}
