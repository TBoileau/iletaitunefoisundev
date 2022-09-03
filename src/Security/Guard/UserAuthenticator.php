<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Security\Guard;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

final class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('security_login');
    }

    public function authenticate(Request $request): Passport
    {
        /** @var string $email */
        $email = $request->request->get('email');

        /** @var string $password */
        $password = $request->request->get('password');

        /** @var string $password */
        $rememberMe = $request->request->getBoolean('remember_me', false);

        /** @var string $csrfToken */
        $csrfToken = $request->request->get('_csrf_token');

        /** @var array<array-key, BadgeInterface> $badges */
        $badges = [new CsrfTokenBadge('authenticate', $csrfToken)];

        if ($rememberMe) {
            $badges[] = new RememberMeBadge();
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            $badges
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('index'));
    }
}
