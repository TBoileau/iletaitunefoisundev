<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Mailer;

use IncentiveFactory\Domain\Player\Player;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class ValidRegistrationEmail extends TemplatedEmail
{
    public function __construct(Player $player)
    {
        parent::__construct();

        $this
            ->to($player->email())
            ->subject('Valider votre inscription !')
            ->htmlTemplate('emails/valid_registration.html.twig')
            ->context(['player' => $player]);
    }
}
