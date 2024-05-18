<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\PictureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectController extends AbstractController
{
    #[Route('/dashboard/projet', name: 'admin_project_add')]
    public function add(Request $request, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $project = new Project();

        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            /**
             * @var UploadedFile $thumbnail
             * @var UploadedFile $mobilePicture
             * @var UploadedFile $desktopPicture
             */
            $thumbnail = $projectForm->get('thumbnail')->getData();
            $mobilePicture = $projectForm->get('mobilePicture')->getData();
            $desktopPicture = $projectForm->get('desktopPicture')->getData();

            $thumbnail = $pictureService->addFile($thumbnail, 'work/thumbnail');
            $mobilePicture = $pictureService->addFile($mobilePicture, 'work/mobilePicture');
            $desktopPicture = $pictureService->addFile($desktopPicture, 'work/desktopPicture');

            $slug = $slugger->slug($project->getTitle())->lower();
            $project->setSlug($slug)->setThumbnail($thumbnail)->setMobilePicture($mobilePicture)->setDesktopPicture($desktopPicture);
            dd($project);
        }


        $projectForm->createView();
        return $this->render('admin/dashboard/project.html.twig', compact('projectForm'));
    }
}
