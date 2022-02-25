<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\GetMapByRegion;

use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Gateway\QuestGateway;
use Laudis\Neo4j\Contracts\ClientInterface;
use Laudis\Neo4j\Databags\SummarizedResult;
use Laudis\Neo4j\Types\CypherMap;

final class GetMapByRegion
{
    /**
     * @param QuestGateway<Quest> $questGateway
     */
    public function __construct(private ClientInterface $neo4jClient, private QuestGateway $questGateway)
    {
    }

    public function __invoke(Region $region): Map
    {
        /** @var SummarizedResult<array{q1: string, q2: string, type: string}> $relations */
        $relations = $this->neo4jClient->run('
            MATCH (n1:Quest)-[n:NEXT|RELATIVE]-(n2:Quest) 
            WHERE n1.region=$region and n2.region=$region
            RETURN n1.id as q1, n2.id as q2, type(n) as type
        ', ['region' => $region->getId()]);

        $map = new Map(region: $region, firstQuest: $region->getFirstQuest()?->getId());

        foreach ($relations as $relation) {
            if (!$map->has((int) $relation['q1'])) {
                /** @var Quest $quest */
                $quest = $this->questGateway->getQuestById((int) $relation['q1']);
                $map->add($quest);
            }

            if ($map->has((int) $relation['q2'])) {
                /** @var Quest $quest */
                $quest = $this->questGateway->getQuestById((int) $relation['q2']);
                $map->add($quest);
            }

            $map->attach((int) $relation['q1'], (int) $relation['q2'], $relation['type']);
        }

        return $map;
    }
}
