<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared;

use App\Domain\Security\Command\RegistrationHandler;
use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Domain\Security\Message\Registration;
use App\Domain\Shared\Command\LinkHandler;
use App\Domain\Shared\Entity\Node;
use App\Domain\Shared\Gateway\NodeGateway;
use App\Domain\Shared\Message\Link;
use App\Domain\Shared\Uuid\UuidGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final class LinkTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLinkNodes(): void
    {

        $from = new Node();
        $from->setId(Uuid::v4());
        $from->setTitle('Title 51');
        $from->setSlug('title-51');

        $to = new Node();
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

        $command = new LinkHandler($nodeGateway);

        $command($link);

        self::assertCount(1, $from->getSiblings());
        self::assertTrue($from->getSiblings()->contains($to));

        self::assertCount(1, $to->getSiblings());
        self::assertTrue($to->getSiblings()->contains($from));
    }
}
