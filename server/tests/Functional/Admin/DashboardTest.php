<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Entity\Administrator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DashboardTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldRedirectToAdminLogin(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/admin');

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        self::assertRouteSame('admin_security_login');
    }

    /**
     * @test
     */
    public function shouldShowDashboard(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        $client->request(Request::METHOD_GET, '/admin');

        self::assertResponseIsSuccessful();
    }
}
