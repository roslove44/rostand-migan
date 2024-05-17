<?php

namespace App\Controller\Admin;

use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/dashboard/projet', name: 'admin_project_add')]
    public function add(Request $request): Response
    {
        $projectForm = $this->createForm(ProjectType::class);
        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            dd($projectForm->getData());
        }


        $projectForm->createView();
        return $this->render('admin/dashboard/project.html.twig', compact('projectForm'));
    }
}
