<?php

namespace App\Controller\Admin;

use App\Entity\Filter;
use App\Repository\FilterRepository;
use App\Repository\StackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FilterController extends AbstractController
{

    #[Route('/api/filter/add', name: 'admin_filter_add', methods: ['POST'])]
    public function addFilter(Request $request, EntityManagerInterface $em): Response
    {
        $filter = $request->request->get('addFilter');
        if (!$filter) {
            $this->addFlash('danger', 'Filtre renseigné invalide');
            return $this->redirectToRoute('admin_filter');
        }
        $filter = ucwords($this->purify_input($filter));
        $stack = new Filter();
        $stack->setName($filter);
        try {
            $em->persist($stack);
            $em->flush();
            $this->addFlash('success', 'Filtre enregistré avec succès.');
        } catch (\Throwable $th) {
            $this->addFlash('warning', 'Erreur ' . $th->getMessage());
            return $this->redirectToRoute('admin_filter');
        } finally {
            return $this->redirectToRoute('admin_filter');
        }
    }

    #[Route('/api/filter/update/', name: 'admin_filter_update')]
    public function updateFilter(Request $request, EntityManagerInterface $em, FilterRepository $filterRepository): Response
    {
        $name = $request->request->get('newName');
        $id = (int) $request->request->get('filter');
        $filter = $filterRepository->findOneBy(['id' => $id]);

        if (!$name && !$filter) {
            $this->addFlash('danger', 'Paramètres invalides');
            return $this->redirectToRoute('admin_filter');
        }

        try {
            $filter->setName(ucwords($this->purify_input($name)));
            $em->persist($filter);
            $em->flush();
            $this->addFlash('success', 'Le filtre a bien été modifié.');
            return $this->redirectToRoute('admin_filter');
        } catch (\Throwable $th) {
            $this->addFlash('danger', 'Erreur : ' . $th->getMessage());
            return $this->redirectToRoute('admin_filter');
        }
    }

    #[Route('/api/filter/delete/{id}', name: 'admin_filter_delete', requirements: ['id' => '\d+'])]
    public function deleteFilter(int $id, Request $request, EntityManagerInterface $em, FilterRepository $filterRepository): Response
    {
        $filter = $filterRepository->findOneBy(['id' => $id]);

        if (!$filter) {
            $this->addFlash('danger', 'Paramètres invalides');
            return $this->redirectToRoute('admin_filter');
        }

        try {
            $em->remove($filter);
            $em->flush();
            $this->addFlash('success', 'Le filtre a bien été supprimé.');
            return $this->redirectToRoute('admin_filter');
        } catch (\Throwable $th) {
            $this->addFlash('danger', 'Erreur : ' . $th->getMessage());
            return $this->redirectToRoute('admin_filter');
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
