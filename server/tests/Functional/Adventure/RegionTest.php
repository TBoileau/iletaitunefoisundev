<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
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
    public function shouldReturnMapOfRegion(): void
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
  "relations": [
    {
      "from": 1,
      "to": 4,
      "type": "RELATIVE"
    },
    {
      "from": 1,
      "to": 2,
      "type": "NEXT"
    },
    {
      "from": 2,
      "to": 5,
      "type": "RELATIVE"
    },
    {
      "from": 2,
      "to": 3,
      "type": "NEXT"
    },
    {
      "from": 2,
      "to": 1,
      "type": "NEXT"
    },
    {
      "from": 3,
      "to": 4,
      "type": "RELATIVE"
    },
    {
      "from": 3,
      "to": 2,
      "type": "NEXT"
    },
    {
      "from": 4,
      "to": 5,
      "type": "RELATIVE"
    },
    {
      "from": 4,
      "to": 3,
      "type": "RELATIVE"
    },
    {
      "from": 4,
      "to": 1,
      "type": "RELATIVE"
    },
    {
      "from": 5,
      "to": 4,
      "type": "RELATIVE"
    },
    {
      "from": 5,
      "to": 2,
      "type": "RELATIVE"
    }
  ],
  "region": {
    "id": 1,
    "name": "Region 1",
    "continent": {
      "id": 1,
      "name": "Continent 1",
      "world": {
        "id": 1,
        "name": "Monde"
      }
    }
  },
  "firstQuest": 1
}
EOF
);
    }
}
