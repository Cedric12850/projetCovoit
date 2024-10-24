<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Entity\CarUser;
use App\Entity\Comment;
use App\Entity\Reservation;
use App\Entity\Specificity;
use App\Entity\Step;
use App\Entity\Town;
use App\Entity\Trip;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ProjetCovoit');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Trip', 'fas fa-suitcase', Trip::class);
        yield MenuItem::linkToCrud('Step', 'fas fa-list', Step::class);
        yield MenuItem::linkToCrud('Town', 'fas fa-map', Town::class);
        yield MenuItem::linkToCrud('Reservation', 'fas fa-list', Reservation::class);
        yield MenuItem::linkToCrud('Car', 'fas fa-car', Car::class);
        yield MenuItem::linkToCrud('CarUser', 'fas fa-id-card', CarUser::class);
        yield MenuItem::linkToCrud('Specificity', 'fas fa-list', Specificity::class);
        yield MenuItem::linkToCrud('Comment', 'fas fa-comment', Comment::class);
    }
}
