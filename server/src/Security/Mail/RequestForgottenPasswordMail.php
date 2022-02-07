<?php

declare(strict_types=1);

namespace App\Security\Mail;

use App\Security\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

class RequestForgottenPasswordMail extends TemplatedEmail
{
    public function __construct(private User $user, Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);

        $this
            ->to($this->user->getEmail())
            ->text(sprintf('Here is your token to reset your password : %s', $user->getForgottenPasswordToken()))
            ;
    }
}
