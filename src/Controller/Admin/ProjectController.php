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
            //Check Ckeditor Field 
            if (!$project->getDescription()) {
                $this->addFlash('danger', "Veuillez à ce que le champ description ne soit pas nul.");
                return $this->redirectToRoute('admin_project_add');
            }
            // Get Files & Add Files to Server
            if ($thumbnail = $projectForm->get('thumbnail')->getData()) {
                $thumbnail = $pictureService->addFile($thumbnail, 'work/thumbnail');
                $project->setThumbnail($thumbnail);
            }

            if ($mobilePicture = $projectForm->get('mobilePicture')->getData()) {
                $mobilePicture = $pictureService->addFile($mobilePicture, 'work/mobilePicture');
                $project->setMobilePicture($mobilePicture);
            }

            if ($desktopPicture = $projectForm->get('desktopPicture')->getData()) {
                $desktopPicture = $pictureService->addFile($desktopPicture, 'work/desktopPicture');
                $project->setDesktopPicture($desktopPicture);
            }

            // Set Project
            $slug = $slugger->slug($project->getTitle())->lower();
            $project->setSlug($slug);

            //Created At
            $project->setCreatedAt(new \DateTimeImmutable());

            try {
                $em->persist($project);
                $em->flush();
                $this->addFlash('success', "Projet " . $project->getTitle() . " ajouté avec succès.");
                return $this->redirectToRoute('admin_main');
            } catch (\Throwable $th) {
                $pictureService->removeFile($project->getThumbnail());
                if ($project->getMobilePicture()) {
                    $pictureService->removeFile($project->getMobilePicture());
                }
                $pictureService->removeFile($project->getDesktopPicture());
                $this->addFlash('danger', "Une erreur s'est produite : " . $th->getMessage());
                return $this->redirectToRoute('admin_project_add');
            }
        }

        $projectForm->createView();
        return $this->render('admin/dashboard/project.html.twig', compact('projectForm'));
    }

    #[Route('/dashboard/projet/edit/{slug}', name: 'admin_project_edit')]
    public function edit(Request $request, Project $project = null, SluggerInterface $slugger, PictureService $pictureService, EntityManagerInterface $em): Response
    {
        if ($project === null) {
            $this->addFlash('danger', "Aucun projet ne correspond à l'url auquel vous essayez d'accéder");
            return $this->redirectToRoute('admin_main', [], Response::HTTP_MOVED_PERMANENTLY);
        }
        $oldThumbnail = $project->getThumbnail();
        $oldMobilePicture = $project->getMobilePicture();
        $oldDesktopPicture = $project->getDesktopPicture();

        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            //Check Ckeditor Field 
            if (!$project->getDescription()) {
                $this->addFlash('danger', "Veuillez à ce que le champ description ne soit pas nul.");
                return $this->redirectToRoute('admin_project_edit');
            }
            // Get Files
            if ($thumbnail = $projectForm->get('thumbnail')->getData()) {
                $thumbnail = $pictureService->addFile($thumbnail, 'work/thumbnail');
                $pictureService->removeFile($oldThumbnail, 'work/thumbnail');
                $project->setThumbnail($thumbnail);
            }
            if ($mobilePicture = $projectForm->get('mobilePicture')->getData()) {
                $mobilePicture = $pictureService->addFile($mobilePicture, 'work/mobilePicture');
                $pictureService->removeFile($oldMobilePicture, 'work/mobilePicture');
                $project->setMobilePicture($mobilePicture);
            }
            if ($desktopPicture = $projectForm->get('desktopPicture')->getData()) {
                $desktopPicture = $pictureService->addFile($desktopPicture, 'work/desktopPicture');
                $pictureService->removeFile($oldDesktopPicture, 'work/desktopPicture');
                $project->setDesktopPicture($desktopPicture);
            }

            // Set Project
            $slug = $slugger->slug($project->getTitle())->lower();
            $project->setSlug($slug);

            // Update Date
            $project->setUpdatedAt(new \DateTimeImmutable());

            try {
                $em->persist($project);
                $em->flush();
                $this->addFlash('success', "Projet " . $project->getTitle() . " modifié avec succès.");
                return $this->redirectToRoute('admin_main', [], Response::HTTP_MOVED_PERMANENTLY);
            } catch (\Throwable $th) {
                $pictureService->removeFile($project->getThumbnail());
                $pictureService->removeFile($project->getMobilePicture());
                $pictureService->removeFile($project->getDesktopPicture());
                $this->addFlash('danger', "Une erreur s'est produite : " . $th->getMessage());
                return $this->redirectToRoute('admin_main');
            }
        }


        $projectForm->createView();
        $work = $project;
        return $this->render('admin/dashboard/editProject.html.twig', compact('projectForm', 'work'));
    }

    #[Route('/dashboard/projet/delete/{slug}', name: 'admin_project_delete')]
    public function delete(Project $project = null, PictureService $pictureService, EntityManagerInterface $em): Response
    {
        if ($project === null) {
            $this->addFlash('danger', "Aucun projet ne correspond à l'url auquel vous essayez d'accéder");
            return $this->redirectToRoute('admin_main', [], Response::HTTP_MOVED_PERMANENTLY);
        }
        $thumbnail = $project->getThumbnail();
        $mobilePicture = $project->getMobilePicture();
        $desktopPicture = $project->getDesktopPicture();
        try {
            $pictureService->removeFile($thumbnail);
            $pictureService->removeFile($mobilePicture);
            $pictureService->removeFile($desktopPicture);

            $em->remove($project);
            $em->flush();

            $this->addFlash('success', 'Le filtre a bien été supprimé.');
        } catch (\Throwable $th) {
            $this->addFlash('danger', "Une erreur s'est produite : " . $th->getMessage());
        } finally {
            return $this->redirectToRoute('admin_main');
        }
    }
}
