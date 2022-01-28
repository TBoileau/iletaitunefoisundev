<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\SaveCrudController;
use App\Admin\Entity\Administrator;
use App\Adventure\Entity\Save;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SaveTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfSaves(): void
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
                ->setController(SaveCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldShowDetailOfSave(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Save $save */
        $save = $entityManager->getRepository(Save::class)->findOneBy([]);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(SaveCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($save->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }
}
