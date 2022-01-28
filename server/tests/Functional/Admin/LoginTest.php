<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Profiler\Profile;

final class LoginTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldBeAuthenticated(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/admin/security/login');

        self::assertResponseIsSuccessful();

        $client->enableProfiler();

        $client->submitForm('Se connecter', self::createData());

        self::assertAuthenticated($client);
    }

    /**
     * @param array<string, string> $formData
     *
     * @test
     *
     * @dataProvider provideInvalidData
     */
    public function shouldNotBeAuthenticatedDueToInvalidData(array $formData): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/admin/security/login');

        self::assertResponseIsSuccessful();

        $client->enableProfiler();

        $client->submitForm('Se connecter', $formData);

        self::assertNotAuthenticated($client);
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        yield 'wrong email' => [self::createData(['_username' => 'fail@email.com'])];
        yield 'empty email' => [self::createData(['_username' => ''])];
        yield 'wrong password' => [self::createData(['_password' => 'fail'])];
        yield 'empty password' => [self::createData(['_password' => ''])];
    }

    private static function assertAuthenticated(KernelBrowser $client): void
    {
        if (($profile = $client->getProfile()) instanceof Profile) {
            /** @var SecurityDataCollector $securityCollector */
            $securityCollector = $profile->getCollector('security');

            self::assertTrue($securityCollector->isAuthenticated());
        }
    }

    private static function assertNotAuthenticated(KernelBrowser $client): void
    {
        if (($profile = $client->getProfile()) instanceof Profile) {
            /** @var SecurityDataCollector $securityCollector */
            $securityCollector = $profile->getCollector('security');

            self::assertFalse($securityCollector->isAuthenticated());
        }
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(array $extra = []): array
    {
        return $extra + [
                '_username' => 'admin+1@email.com',
                '_password' => 'password',
            ];
    }
}
