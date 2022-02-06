<?php

declare(strict_types=1);

namespace App\Tests\Functional\Content;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Entity\Quest;
use App\Content\Entity\Quiz;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;

final class QuizTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function getItemShouldReturnQuiz(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var Quiz $quiz */
        $quiz = $this->findOneBy(Quiz::class, [[]]);
        $client->request(Request::METHOD_GET, sprintf('/api/content/quizzes/%s', $quiz->getId()));
        self::assertResponseIsSuccessful();
        self::assertMatchesResourceItemJsonSchema(Quest::class);
    }
}
