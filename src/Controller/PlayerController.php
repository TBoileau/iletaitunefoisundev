<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use Doctrine\DBAL\Types\ConversionException;
use IncentiveFactory\Domain\Player\Register\Registration;
use IncentiveFactory\Domain\Player\ValidRegistration\ValidationOfRegistration;
use IncentiveFactory\IlEtaitUneFoisUnDev\Form\RegistrationType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
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

    #[Route('/valid-registration/{registrationToken}', name: 'valid_registration', methods: [Request::METHOD_GET])]
    public function validRegistration(string $registrationToken): RedirectResponse
    {
        try {
            $this->execute(new ValidationOfRegistration($registrationToken));
        } catch (ValidationFailedException|ConversionException) {
            $this->addFlash('error', 'Une erreur est survenue lors de la validation de votre inscription.');

            return $this->redirectToRoute('index');
        }

        return $this->redirectToRoute('security_login');
    }
}
