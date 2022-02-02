<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\Region;

use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\Entity\Region;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class GetRegionTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsRegion(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = $client->getContainer()->get(RegionRepository::class);

        $region = $regionRepository->findOneBy([]);

        /** @var array{id: string, name: string} $content */
        $content = self::get($client, sprintf('/api/adventure/regions/%s', $region->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertTrue(Ulid::isValid($content['id']));
        self::assertEquals($region->getName(), $content['name']);
    }
}
