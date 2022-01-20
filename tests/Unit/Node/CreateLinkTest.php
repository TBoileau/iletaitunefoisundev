<?php

declare(strict_types=1);

namespace App\Tests\Unit\Node;

use App\Domain\Course\Entity\Course;
use App\Domain\Node\Command\CreateLinkHandler;
use App\Domain\Node\Gateway\NodeGateway;
use App\Domain\Node\Message\Link;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class CreateLinkTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateLinkBetweenTwoNodes(): void
    {
        $from = new Course();
        $from->setId(Uuid::v4());
        $from->setTitle('Title 51');
        $from->setSlug('title-51');

        $to = new Course();
        $to->setId(Uuid::v4());
        $to->setTitle('Title 52');
        $to->setSlug('title-52');

        $link = new Link();
        $link->setFrom($from);
        $link->setTo($to);

        $nodeGateway = self::createMock(NodeGateway::class);
        $nodeGateway
            ->expects(self::once())
            ->method('update');

        $command = new CreateLinkHandler($nodeGateway);

        $command($link);

        self::assertCount(1, $from->getSiblings());
        self::assertTrue($from->getSiblings()->contains($to));

        self::assertCount(1, $to->getSiblings());
        self::assertTrue($to->getSiblings()->contains($from));
    }
}
