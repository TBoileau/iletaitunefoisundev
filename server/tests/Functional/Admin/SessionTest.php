<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\SessionCrudController;
use App\Admin\Entity\Administrator;
use App\Content\Entity\Quiz\Session;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SessionTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfSessions(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(SessionCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldShowDetailOfSession(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Session $checkpoint */
        $checkpoint = $entityManager->getRepository(Session::class)->findOneBy([]);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(SessionCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($checkpoint->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }
}
