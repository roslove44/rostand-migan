<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pages', name: 'admin_pages_')]
class PagesController extends AbstractController
{

    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('admin/pages/index.html.twig');
    }
    #[Route('/ui-features/buttons.html', name: 'ui_features_buttons')]
    public function ui_features_buttons(): Response
    {
        return $this->render('admin/pages/ui-features/buttons.html.twig');
    }

    #[Route('/ui-features/dropdowns.html', name: 'ui_features_dropdowns')]
    public function ui_features_dropdowns(): Response
    {
        return $this->render('admin/pages/ui-features/dropdowns.html.twig');
    }

    #[Route('/ui-features/typography.html', name: 'ui_features_typography')]
    public function ui_features_typography(): Response
    {
        return $this->render('admin/pages/ui-features/typography.html.twig');
    }


    #[Route('/forms/basic_elements.html', name: 'forms')]
    public function forms(): Response
    {
        return $this->render('admin/pages/forms/basic_elements.html.twig');
    }

    #[Route('/icons/font-awesome.html', name: 'icons')]
    public function icons(): Response
    {
        return $this->render('admin/pages/icons/font-awesome.html.twig');
    }

    #[Route('/tables/basic-table.html', name: 'tables')]
    public function tables(): Response
    {
        return $this->render('admin/pages/tables/basic-table.html.twig');
    }


    #[Route('/charts/chartjs.html', name: 'charts')]
    public function charts(): Response
    {
        return $this->render('admin/pages/charts/chartjs.html.twig');
    }

    #[Route('/samples/error-404.html', name: 'samples_error_404')]
    public function error404(): Response
    {
        return $this->render('admin/pages/samples/error-404.html.twig');
    }

    #[Route('/samples/error-500.html', name: 'samples_error_500')]
    public function error500(): Response
    {
        return $this->render('admin/pages/samples/error-500.html.twig');
    }

    #[Route('/samples/login.html', name: 'samples_login')]
    public function login(): Response
    {
        return $this->render('admin/pages/samples/login.html.twig');
    }
    #[Route('/samples/register.html', name: 'samples_register')]
    public function register(): Response
    {
        return $this->render('admin/pages/samples/register.html.twig');
    }
}
