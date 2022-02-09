<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Admin\Controller\CheckpointCrudController;
use App\Admin\Entity\Administrator;
use App\Adventure\Entity\Checkpoint;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CheckpointTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldShowListOfCheckpoints(): void
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
                ->setController(CheckpointCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function shouldShowDetailOfCheckpoint(): void
    {
        $client = self::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        /** @var Administrator $admin */
        $admin = $entityManager->getRepository(Administrator::class)->findOneBy(['email' => 'admin+1@email.com']);

        $client->loginUser($admin, 'admin');

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        /** @var Checkpoint $checkpoint */
        $checkpoint = $entityManager->getRepository(Checkpoint::class)
            ->createQueryBuilder('c')
            ->where('c.finishedAt IS NOT NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(CheckpointCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($checkpoint->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();

        /** @var Checkpoint $checkpoint */
        $checkpoint = $entityManager->getRepository(Checkpoint::class)->findOneBy(['finishedAt' => null]);

        $client->request(
            'GET',
            $adminUrlGenerator
                ->setController(CheckpointCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($checkpoint->getId())
                ->generateUrl()
        );

        self::assertResponseIsSuccessful();
    }
}
