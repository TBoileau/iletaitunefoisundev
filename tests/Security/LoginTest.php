<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Security;

use Generator;
use IncentiveFactory\IlEtaitUneFoisUnDev\Tests\HelpersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class LoginTest extends WebTestCase
{
    use HelpersTrait;

    public function testShouldLoggedUserAndRedirectToIndex(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');
        $client->submitForm('Se connecter', self::createFormData());

        self::assertResponseRedirects('/');
        self::assertIsAuthenticated(true);
    }

    public function testShouldNotLoggedAnUserThatNotValidateItsRegistrationAndRedirectToIndex(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');
        $client->submitForm('Se connecter', self::createFormData(email: 'player+11@email.com'));

        self::assertIsAuthenticated(false);
        self::assertResponseRedirects('/login');
    }

    /**
     * @dataProvider provideInvalidFormData
     *
     * @param array{email: string, password: string} $formData
     */
    public function testShouldNotLoggedUserAndRedirectToLogin(array $formData): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');
        $client->submitForm('Se connecter', $formData);

        self::assertIsAuthenticated(false);
        self::assertResponseRedirects('/login');
    }

    /**
     * @return array{email: string, password: string}
     */
    private static function createFormData(string $email = 'player+1@email.com', string $password = 'password'): array
    {
        return ['email' => $email, 'password' => $password];
    }

    /**
     * @return Generator<string, array<array-key, array{email: string, password: string}>>
     */
    public function provideInvalidFormData(): Generator
    {
        yield 'invalid email' => [self::createFormData(email: 'fail@email.com')];
        yield 'invalid password' => [self::createFormData(password: 'fail')];
    }
}
