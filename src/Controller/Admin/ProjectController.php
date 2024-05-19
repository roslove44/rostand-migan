<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectController extends AbstractController
{
    #[Route('/dashboard/projet', name: 'admin_project_add')]
    public function add(Request $request, SluggerInterface $slugger, PictureService $pictureService, EntityManagerInterface $em): Response
    {
        $project = new Project();

        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            // Get Files 
            $thumbnail = $projectForm->get('thumbnail')->getData();
            $mobilePicture = $projectForm->get('mobilePicture')->getData();
            $desktopPicture = $projectForm->get('desktopPicture')->getData();

            // Add Files to Server
            $thumbnail = $pictureService->addFile($thumbnail, 'work/thumbnail');
            $mobilePicture = $pictureService->addFile($mobilePicture, 'work/mobilePicture');
            $desktopPicture = $pictureService->addFile($desktopPicture, 'work/desktopPicture');

            // Set Project
            $slug = $slugger->slug($project->getTitle())->lower();
            $project->setSlug($slug)->setThumbnail($thumbnail)->setMobilePicture($mobilePicture)->setDesktopPicture($desktopPicture);

            try {
                $em->persist($project);
                $em->flush();
                $this->addFlash('success', "Projet " . $project->getTitle() . " ajouté avec succès.");
                return $this->redirectToRoute('admin_main');
            } catch (\Throwable $th) {
                $pictureService->removeFile($project->getThumbnail());
                $pictureService->removeFile($project->getMobilePicture());
                $pictureService->removeFile($project->getDesktopPicture());
                $this->addFlash('danger', "Une erreur s'est produite : " . $th->getMessage());
                return $this->redirectToRoute('admin_project_add');
            }
        }


        $projectForm->createView();
        return $this->render('admin/dashboard/project.html.twig', compact('projectForm'));
    }
}
