<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Shared\Entity\Node;
use App\Domain\Shared\Uuid\UuidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class NodeFixtures extends Fixture
{
    public function __construct(private UuidGeneratorInterface $uuidGenerator)
    {
    }

    public function load(ObjectManager $manager)
    {
        /** @var array<array-key, Node> $nodes */
        $nodes = [];

        for ($i = 1; $i <= 50; ++$i) {
            $node = $this->createNode($i);
            $manager->persist($node);
            $nodes[] = $node;
        }

        foreach ($nodes as $i => $node) {
            if ($i < count($nodes) - 1) {
                $node->addSibling($nodes[$i + 1]);
            }
        }

        $manager->flush();
    }

    private function createNode(int $index): Node
    {

        $node = new Node();
        $node->setId($this->uuidGenerator->generate());
        $node->setTitle(sprintf('Node %d', $index));
        $node->setSlug(sprintf('title-%d', $index));

        return $node;
    }
}
