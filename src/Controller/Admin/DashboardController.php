<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ouvrage;
use App\Entity\CollectionOuvrage;
use App\Entity\Chapitre;
use App\Entity\Ressource;
use App\Entity\Section;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Biblio');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Collection', 'fas fa-list', CollectionOuvrage::class);
        yield MenuItem::linkToCrud('Ouvrage', 'fas fa-list', Ouvrage::class);
        yield MenuItem::linkToCrud('Chapitre', 'fas fa-list', Chapitre::class);
        yield MenuItem::linkToCrud('Section', 'fas fa-list', Section::class);
        yield MenuItem::linkToCrud('Ressource', 'fas fa-list', Ressource::class);
    }
}
