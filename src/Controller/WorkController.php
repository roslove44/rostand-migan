<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkController extends AbstractController
{
    #[Route('/work', name: 'app_work')]
    public function index(): Response
    {
        return $this->render('work/index.html.twig', [
            'controller_name' => 'ProjectsController',
        ]);
    }
}
