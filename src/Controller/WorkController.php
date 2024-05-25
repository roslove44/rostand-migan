<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\FilterRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkController extends AbstractController
{
    #[Route('/work', name: 'app_work')]
    public function index(ProjectRepository $projectRepository, FilterRepository $filterRepository): Response
    {
        $projects = $projectRepository->findAll();
        $filters = $filterRepository->findAll();
        return $this->render('work/index.html.twig', [
            'works' => $projects,
            'filters' => $filters,
        ]);
    }

    #[Route('/work/{slug}', name: 'app_work_detail')]
    public function detail(Project $project): Response
    {
        $work = $project;
        return $this->render('work/work.html.twig', compact('work'));
    }
}
