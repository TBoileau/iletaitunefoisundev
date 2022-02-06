<?php

declare(strict_types=1);

namespace App\Tests\Functional\Content;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Entity\Quest;
use App\Content\Entity\Answer;
use App\Content\Entity\Quiz;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class QuizTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function shouldTakeQuiz(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var Quiz $quiz */
        $quiz = $this->findOneBy(Quiz::class, [[]]);
        $response = $client->request(
            Request::METHOD_POST,
            sprintf('/api/content/quizzes/%s/start', $quiz->getId()),
            ['json' => []]
        );
        self::assertResponseStatusCodeSame(HttpResponse::HTTP_CREATED);
        self::assertMatchesResourceItemJsonSchema(Quiz\Session::class);

        /** @var array{responses: array<array-key, string>} $data */
        $data = $response->toArray();

        foreach ($data['responses'] as $i => $responseIri) {
            /** @var array{question: array{answers: array<array-key, array{id: int}>}} $data */
            $responseData = $client->request(Request::METHOD_GET, $responseIri)->toArray();
            /** @var array{valid: bool} $response */
            $response = $client->request(Request::METHOD_PUT, $responseIri, ['json' => [
                'answers' => [
                    $this->findIriBy(
                        Answer::class,
                        ['id' => $responseData['question']['answers'][0 === $i % 2 ? 1 : 0]]
                    ),
                ],
            ]])->toArray();

            self::assertEquals(0 === $i % 2, $response['valid']);
        }
    }

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
