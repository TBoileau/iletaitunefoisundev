<?php

declare(strict_types=1);

namespace App\Content\UseCase\StartQuizSession;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Gateway\CheckpointGateway;
use App\Content\Entity\Quiz\Response;
use App\Content\Entity\Quiz\Session;
use App\Content\Gateway\SessionGateway;
use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class StartQuizSessionHandler implements MessageHandlerInterface
{
    /**
     * @param SessionGateway<Session>       $sessionGateway
     * @param CheckpointGateway<Checkpoint> $checkpointGateway
     */
    public function __construct(private SessionGateway $sessionGateway, private CheckpointGateway $checkpointGateway)
    {
    }

    public function __invoke(StartQuizSessionInput $respondQuiz): Session
    {
        $session = new Session();
        $session->setQuiz($respondQuiz->quiz);
        $session->setPlayer($respondQuiz->player);
        $session->setStartedAt(new DateTimeImmutable());

        foreach ($session->getQuiz()->getQuestions() as $question) {
            $response = new Response();
            $response->setQuestion($question);
            $response->setSession($session);
            $session->getResponses()->add($response);
        }

        $this->sessionGateway->start($session);

        $this->checkpointGateway->attachSession($session);

        return $session;
    }
}
