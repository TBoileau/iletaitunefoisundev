<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Domain\Shared\Entity\Node;
use App\Domain\Shared\Uuid\UuidGeneratorInterface;
use App\Infrastructure\Repository\NodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class NodeRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function updateShouldAddLinkBetweenTwoNodes(): void
    {
        self::bootKernel();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        /** @var NodeRepository<Node> $nodeRepository */
        $nodeRepository = self::getContainer()->get(NodeRepository::class);

        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = self::getContainer()->get(UuidGeneratorInterface::class);

        $from = new Node();
        $from->setId($uuidGenerator->generate());
        $from->setTitle('Title 51');
        $from->setSlug('title-51');
        $entityManager->persist($from);

        $to = new Node();
        $to->setId($uuidGenerator->generate());
        $to->setTitle('Title 52');
        $to->setSlug('title-52');
        $entityManager->persist($to);

        $entityManager->flush();

        $from->addSibling($to);

        $nodeRepository->update();

        /** @var Node $from */
        $from = $nodeRepository->findOneBy(['slug' => 'title-51']);

        /** @var Node $to */
        $to = $nodeRepository->findOneBy(['slug' => 'title-52']);

        self::assertCount(1, $from->getSiblings());
        self::assertTrue($from->getSiblings()->contains($to));

        self::assertCount(1, $to->getSiblings());
        self::assertTrue($to->getSiblings()->contains($from));
    }
}
