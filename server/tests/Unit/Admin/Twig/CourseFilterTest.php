<?php

declare(strict_types=1);

namespace App\Tests\Unit\Admin\Twig;

use App\Admin\Twig\CourseFilter;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

final class CourseFilterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnFilters(): void
    {
        $courseFilter = new CourseFilter();

        self::assertEquals(
            [new TwigFilter('youtube', [$courseFilter, 'youtube'])],
            $courseFilter->getFilters()
        );
    }

    /**
     * @test
     */
    public function shouldReturnYoutubeId(): void
    {
        $courseFilter = new CourseFilter();

        self::assertSame(
            '-S94RNjjb4I',
            $courseFilter->youtube('https://www.youtube.com/watch?v=-S94RNjjb4I')
        );
    }
}
