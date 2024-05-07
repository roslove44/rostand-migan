<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }


    #[Route('/contact', name: 'app_main_contact')]
    public function contact(Request $request): Response
    {
        $contactForm = $this->createForm(ContactType::class);
        $emptyForm = clone $contactForm;

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {


            $this->addFlash('success', "You win");
            return $this->redirectToRoute('app_main_contact');
        }


        return $this->render('main/contact.html.twig', compact('contactForm'));
    }
}
