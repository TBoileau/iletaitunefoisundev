<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/admin/security', name: 'admin_security_')]
final class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
            'translation_domain' => 'admin',
            'page_title' => 'iletaitunefoisundev',
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('admin_dashboard'),
            'username_label' => 'Email',
            'password_label' => 'Mot de passe',
            'sign_in_label' => 'Se connecter',
            'remember_me_enabled' => true,
            'remember_me_checked' => true,
            'remember_me_label' => 'Se souvenir de moi',
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    #[Route('/logout', name: 'logout', methods: [Request::METHOD_GET])]
    public function logout(): void
    {
    }
}
