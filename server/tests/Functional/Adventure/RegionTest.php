<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\UseCase\GetMapByRegion\Map;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;

final class RegionTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function getCollectionShouldReturnWorlds(): void
    {
        $client = self::createAuthenticatedClient();
        $client->request(Request::METHOD_GET, '/api/adventure/regions/1/map');
        self::assertResponseIsSuccessful();
        self::assertJson(<<<EOF
{
  "quests": {
    "1": {
      "id": 1,
      "name": "Quest 1",
      "region": "/api/adventure/regions/1",
      "course": {
        "youtubeUrl": "https://www.youtube.com/watch?v=-S94RNjjb4I",
        "description": "Description 1",
        "content": "Content 1",
        "title": "Course 1"
      },
      "quiz": "/api/content/quizzes/126",
      "difficultyName": "Easy",
      "typeName": "Main"
    },
    "2": {
      "id": 2,
      "name": "Quest 2",
      "region": "/api/adventure/regions/1",
      "course": {
        "youtubeUrl": "https://www.youtube.com/watch?v=-S94RNjjb4I",
        "description": "Description 2",
        "content": "Content 2",
        "title": "Course 2"
      },
      "quiz": "/api/content/quizzes/127",
      "difficultyName": "Easy",
      "typeName": "Main"
    },
    "3": {
      "id": 3,
      "name": "Quest 3",
      "region": "/api/adventure/regions/1",
      "course": {
        "youtubeUrl": "https://www.youtube.com/watch?v=-S94RNjjb4I",
        "description": "Description 3",
        "content": "Content 3",
        "title": "Course 3"
      },
      "quiz": "/api/content/quizzes/128",
      "difficultyName": "Normal",
      "typeName": "Main"
    },
    "4": {
      "id": 4,
      "name": "Quest 4",
      "region": "/api/adventure/regions/1",
      "course": {
        "youtubeUrl": "https://www.youtube.com/watch?v=-S94RNjjb4I",
        "description": "Description 4",
        "content": "Content 4",
        "title": "Course 4"
      },
      "quiz": "/api/content/quizzes/129",
      "difficultyName": "Normal",
      "typeName": "Side"
    },
    "5": {
      "id": 5,
      "name": "Quest 5",
      "region": "/api/adventure/regions/1",
      "course": {
        "youtubeUrl": "https://www.youtube.com/watch?v=-S94RNjjb4I",
        "description": "Description 5",
        "content": "Content 5",
        "title": "Course 5"
      },
      "quiz": "/api/content/quizzes/130",
      "difficultyName": "Hard",
      "typeName": "Side"
    }
  },
  "relations": {
    "1": {
      "RELATIVE": [
        4
      ],
      "NEXT": [
        2
      ]
    },
    "2": {
      "RELATIVE": [
        5
      ],
      "NEXT": [
        3,
        1
      ]
    },
    "3": {
      "RELATIVE": [
        4
      ],
      "NEXT": [
        2
      ]
    },
    "4": {
      "RELATIVE": [
        5,
        3,
        1
      ]
    },
    "5": {
      "RELATIVE": [
        4,
        2
      ]
    }
  }
}
EOF
);
    }
}
