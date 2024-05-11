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
        return $this->render('work/index.html.twig', []);
    }

    #[Route('/work/details', name: 'app_work_details')]
    public function details(): Response
    {
        return $this->render('work/details.html.twig', []);
    }
}
