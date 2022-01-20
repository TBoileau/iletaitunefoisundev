<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared;

use App\Domain\Course\Entity\Course;
use App\Domain\Shared\Command\RemoveLinkHandler;
use App\Domain\Shared\Gateway\NodeGateway;
use App\Domain\Shared\Message\Link;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class RemoveLinkTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRemoveLinkBetweenTwoNodes(): void
    {
        $from = new Course();
        $from->setId(Uuid::v4());
        $from->setTitle('Title 51');
        $from->setSlug('title-51');

        $to = new Course();
        $to->setId(Uuid::v4());
        $to->setTitle('Title 52');
        $to->setSlug('title-52');

        $from->addSibling($to);

        $link = new Link();
        $link->setFrom($from);
        $link->setTo($to);

        $nodeGateway = self::createMock(NodeGateway::class);
        $nodeGateway
            ->expects(self::once())
            ->method('update');

        $command = new RemoveLinkHandler($nodeGateway);

        $command($link);

        self::assertCount(0, $from->getSiblings());
        self::assertFalse($from->getSiblings()->contains($to));

        self::assertCount(0, $to->getSiblings());
        self::assertFalse($to->getSiblings()->contains($from));
    }
}
