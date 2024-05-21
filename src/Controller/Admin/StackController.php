<?php

namespace App\Controller\Admin;

use App\Entity\Stack;
use App\Repository\StackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StackController extends AbstractController
{

    #[Route('/api/stack/add', name: 'admin_stack_add', methods: ['POST'])]
    public function addStack(Request $request, EntityManagerInterface $em): Response
    {
        $techno = $request->request->get('addTechno');
        if (!$techno) {
            $this->addFlash('danger', 'Techno renseigné invalide');
            return $this->redirectToRoute('admin_stack');
        }
        $techno = ucwords($this->purify_input($techno));
        $stack = new Stack();
        $stack->setName($techno);
        try {
            $em->persist($stack);
            $em->flush();
            $this->addFlash('success', 'Techno enregistré avec succès.');
        } catch (\Throwable $th) {
            $this->addFlash('warning', 'Erreur ' . $th->getMessage());
            return $this->redirectToRoute('admin_stack');
        } finally {
            return $this->redirectToRoute('admin_stack');
        }
    }

    #[Route('/api/stack/update/', name: 'admin_stack_update')] //, requirements: ['id' => '\d+']
    public function updateStack(Request $request, EntityManagerInterface $em, StackRepository $stackRepository): Response
    {
        $name = $request->request->get('newName');
        $id = (int) $request->request->get('techno');
        $stack = $stackRepository->findOneBy(['id' => $id]);

        if (!$name && !$stack) {
            $this->addFlash('danger', 'Paramètres invalides');
            return $this->redirectToRoute('admin_stack');
        }

        try {
            $stack->setName(ucwords($this->purify_input($name)));
            $em->persist($stack);
            $em->flush();
            $this->addFlash('success', 'La techno a bien été modifiée.');
            return $this->redirectToRoute('admin_stack');
        } catch (\Throwable $th) {
            $this->addFlash('danger', 'Erreur : ' . $th->getMessage());
            return $this->redirectToRoute('admin_stack');
        }
    }

    private function purify_input(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}
