<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Tests;

use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\Mailer\DataCollector\MessageDataCollector;

trait HelpersTrait
{
    private static function getClient(): KernelBrowser
    {
        $method = new ReflectionMethod(WebTestCase::class, 'getClient');

        return $method->invoke(null);
    }

    public function assertEmailContains(string $needle): void
    {
        /** @var Profile $profile */
        $profile = self::getClient()->getProfile();
        $collector = $profile->getCollector('mailer');
        self::assertInstanceOf(MessageDataCollector::class, $collector);
        $messages = $collector->getEvents()->getMessages();
        self::assertEmailHtmlBodyContains($messages[0], $needle);
    }

    public function assertFlashBagContains(string $type, string $message): void
    {
        self::assertContains($message, self::getClient()->getRequest()->getSession()->getFlashBag()->get($type));
    }

    public static function assertIsAuthenticated(bool $isAuthenticated): void
    {
        /** @var Profile $profile */
        $profile = self::getClient()->getProfile();
        $collector = $profile->getCollector('security');
        self::assertInstanceOf(SecurityDataCollector::class, $collector);
        self::assertSame($isAuthenticated, $collector->isAuthenticated());
    }
}
