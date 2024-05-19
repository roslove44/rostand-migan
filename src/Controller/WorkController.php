<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkController extends AbstractController
{
    #[Route('/work', name: 'app_work')]
    public function index(): Response
    {
        return $this->render('work/index.html.twig', []);
    }

    #[Route('/work/details', name: 'app_work_details')]
    public function details(): Response
    {
        return $this->render('work/details.html.twig', []);
    }

    #[Route('/work/{slug}', name: 'app_work_detail')]
    public function detail(Project $project): Response
    {
        $work = $project;
        return $this->render('work/work.html.twig', compact('work'));
    }
}
