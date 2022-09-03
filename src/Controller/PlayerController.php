<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use IncentiveFactory\Domain\Player\Register\Registration;
use IncentiveFactory\Domain\Shared\Command\CommandBus;
use IncentiveFactory\IlEtaitUneFoisUnDev\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players', name: 'player_')]
final class PlayerController extends AbstractController
{
    #[Route('/register', name: 'register', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function register(Request $request): Response
    {
        $registration = new Registration();

        $form = $this->createForm(RegistrationType::class, $registration)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->execute($registration);
            return $this->redirectToRoute('index');
        }

        return $this->renderForm('player/register.html.twig', ['form' => $form]);
    }
}
