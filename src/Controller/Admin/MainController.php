<?php

namespace App\Controller\Admin;

use App\Repository\FilterRepository;
use App\Repository\ProjectRepository;
use App\Repository\StackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_main')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $works = $projectRepository->findAll();
        return $this->render('admin/dashboard/index.html.twig', compact('works'));
    }

    #[Route('/dashboard/profil', name: 'admin_profil')]
    public function profil(): Response
    {
        return $this->render('admin/dashboard/profil.html.twig');
    }

    #[Route('/dashboard/stack', name: 'admin_stack')]
    public function stack(StackRepository $stackRepository): Response
    {
        $stack = $stackRepository->findAll();
        return $this->render('admin/dashboard/stack.html.twig', compact('stack'));
    }

    #[Route('/dashboard/filter', name: 'admin_filter')]
    public function filter(FilterRepository $filterRepository): Response
    {
        $filters = $filterRepository->findAll();
        return $this->render('admin/dashboard/filter.html.twig', compact('filters'));
    }
}
