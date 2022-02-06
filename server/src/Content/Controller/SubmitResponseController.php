<?php

declare(strict_types=1);

namespace App\Content\Controller;

use App\Content\Entity\Quiz\Response;
use App\Content\UseCase\SubmitResponse\SubmitResponseInput;

final class SubmitResponseController
{
    public function __invoke(Response $response, SubmitResponseInput $data): SubmitResponseInput
    {
        $data->response = $response;

        return $data;
    }
}
