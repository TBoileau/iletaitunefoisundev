<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests\Security;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Profiler\Profile;

final class LoginTest extends WebTestCase
{
    public function testShouldLoggedUserAndRedirectToIndex(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');

        $client->enableProfiler();

        $client->submitForm('Se connecter', self::createFormData());

        /** @var Profile $profile */
        $profile = $client->getProfile();
        $collector = $profile->getCollector('security');
        self::assertInstanceOf(SecurityDataCollector::class, $collector);
        self::assertTrue($collector->isAuthenticated());
        self::assertResponseRedirects('/');
    }

    public function testShouldNotLoggedAnUserThatNotValidateItsRegistrationAndRedirectToIndex(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');

        $client->enableProfiler();

        $client->submitForm('Se connecter', self::createFormData(email: 'player+11@email.com'));

        /** @var Profile $profile */
        $profile = $client->getProfile();
        $collector = $profile->getCollector('security');
        self::assertInstanceOf(SecurityDataCollector::class, $collector);
        self::assertFalse($collector->isAuthenticated());
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

        $client->enableProfiler();

        $client->submitForm('Se connecter', $formData);

        /** @var Profile $profile */
        $profile = $client->getProfile();
        $collector = $profile->getCollector('security');
        self::assertInstanceOf(SecurityDataCollector::class, $collector);
        self::assertFalse($collector->isAuthenticated());
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
