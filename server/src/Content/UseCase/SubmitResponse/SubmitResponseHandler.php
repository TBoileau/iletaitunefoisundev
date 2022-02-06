<?php

declare(strict_types=1);

namespace App\Content\UseCase\SubmitResponse;

use App\Content\Entity\Answer;
use App\Content\Entity\Quiz\Response;
use App\Content\Entity\Quiz\Session;
use App\Content\Gateway\ResponseGateway;
use App\Content\Gateway\SessionGateway;
use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SubmitResponseHandler implements MessageHandlerInterface
{
    /**
     * @param ResponseGateway<Response> $responseGateway
     * @param SessionGateway<Session>   $sessionGateway
     */
    public function __construct(
        private ResponseGateway $responseGateway,
        private SessionGateway $sessionGateway
    ) {
    }

    public function __invoke(SubmitResponseInput $submitResponse): Response
    {
        $getId = static fn (Answer $answer): ?int => $answer->getId();

        $answerIds = array_map($getId, $submitResponse->answers);

        $goodAnswerIds = $submitResponse->response
            ->getQuestion()
            ->getAnswers()
            ->filter(static fn (Answer $answer): bool => $answer->isGood())
            ->map($getId);

        $submitResponse->response->setRespondedAt(new DateTimeImmutable());
        $submitResponse->response->setValid(
            count($goodAnswerIds) === $goodAnswerIds->count()
            && 0 === count(array_diff($answerIds, $goodAnswerIds->toArray()))
        );

        $this->responseGateway->submit($submitResponse->response);

        if ($submitResponse->response->getSession()->isFinished()) {
            $submitResponse->response->getSession()->setFinishedAt(new DateTimeImmutable());
            $this->sessionGateway->finish($submitResponse->response->getSession());
        }

        return $submitResponse->response;
    }
}
