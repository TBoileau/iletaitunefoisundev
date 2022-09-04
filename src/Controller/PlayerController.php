<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Controller;

use Doctrine\DBAL\Types\ConversionException;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\Register\Registration;
use IncentiveFactory\Domain\Player\UpdateProfile\Profile;
use IncentiveFactory\Domain\Player\ValidRegistration\ValidationOfRegistration;
use IncentiveFactory\IlEtaitUneFoisUnDev\Form\ProfileType;
use IncentiveFactory\IlEtaitUneFoisUnDev\Form\RegistrationType;
use IncentiveFactory\IlEtaitUneFoisUnDev\Http\Uploader\UploaderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\File;
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

    #[IsGranted('ROLE_USER')]
    #[Route('/update-profile', name: 'update_profile', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function updateProfile(Request $request, UploaderInterface $uploader): Response
    {
        /** @var Player $player */
        $player = $this->getPlayer();

        $profile = new Profile($player);

        $form = $this->createForm(ProfileType::class, $profile)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('avatarFile')->getData() instanceof File) {
                $profile->avatar = $uploader->upload($form->get('avatarFile')->getData());
            }

            $this->execute($profile);

            $this->addFlash('success', 'Votre profil a bien été mis à jour.');

            return $this->redirectToRoute('player_update_profile');
        }

        return $this->renderForm('player/update_profile.html.twig', ['form' => $form]);
    }
}
