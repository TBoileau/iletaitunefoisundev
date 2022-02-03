<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Entity\Administrator;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\World;
use App\Content\Entity\Course;
use App\Security\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController extends AbstractDashboardController
{
    #[Route(path: '/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('iletaitunefoisundev');
    }

    /**
     * @return iterable<MenuItemInterface>
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour sur le site', 'fa fa-arrow-left', 'security_login');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Administration', 'fa fa-cogs')->setSubItems([
            MenuItem::linkToCrud('Administrateurs', 'fa fa-user-shield', Administrator::class),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),
        ]);
        yield MenuItem::subMenu('Contenu', 'fa fa-folder-open')->setSubItems([
            MenuItem::linkToCrud('Cours', 'fa fa-chalkboard-teacher', Course::class),
        ]);
        yield MenuItem::subMenu('Aventure', 'fa fa-road')->setSubItems([
            MenuItem::linkToCrud('Mondes', 'fa fa-map', World::class),
            MenuItem::linkToCrud('Continents', 'fa fa-globe-europe', Continent::class),
            MenuItem::linkToCrud('Régions', 'fa fa-map-signs', Region::class),
            MenuItem::linkToCrud('Quêtes', 'fa fa-exclamation-circle', Quest::class),
            MenuItem::linkToCrud('Joueur', 'fa fa-chalkboard-teacher', Player::class),
            MenuItem::linkToCrud('Journaux de bord', 'fa fa-atlas', Journey::class),
            MenuItem::linkToCrud('Checkpoints', 'fa fa-flag-checkered', Checkpoint::class),
        ]);
    }
}
