<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\Quest;

use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\Entity\Region;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetQuestsByRegionTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsQuests(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = self::getContainer()->get(RegionRepository::class);

        /** @var Region $region */
        $region = $regionRepository->findOneBy(['name' => 'Region 1']);

        /** @var array<array-key, array{id: string, name: string}> $content */
        $content = self::get($client, sprintf('/api/adventure/regions/%s/quests', $region->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertCount(5, $content);
    }
}
