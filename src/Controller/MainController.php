<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\SendMailService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }


    #[Route('/contact', name: 'app_main_contact')]
    public function contact(Request $request, SendMailService $mailer, ParameterBagInterface $parameters, LoggerInterface $logger): Response
    {
        $contactForm = $this->createForm(ContactType::class);
        $emptyForm = clone $contactForm;

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $data = $contactForm->getData();
            $message = "Merci " . $data['prospect_name'] . " ! " . "Votre message a bien été reçu. Vous recevrez une réponse sous 24 heures.";

            try {
                $to = $parameters->get("RECIPIENT_MAIL");
                $subject = "Contact par portfolio" . " ce " . date("d F Y à H:i:s");
                $mailer->send($to, $subject, 'contact/to-admin', $data);

                if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
                    $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                    $contactForm = $emptyForm;
                    return $this->renderBlock('_partials/_turbo/_contact.html.twig', 'success_stream', compact('message', 'contactForm'));
                }
            } catch (\Throwable $th) {
                $logger->error("Impossible d'envoyer le mail", ['error' => $th->getMessage()]);
                if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
                    $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                    $contactForm = $emptyForm;
                    $message = "Un problème technique empêche la soumission du formulaire. 
                    N'hésitez pas à me contacter directement sur $to.
                    Merci pour la compréhension !";
                    return $this->renderBlock('_partials/_turbo/_contact.html.twig', 'failed_stream', compact('message', 'contactForm'));
                }
            }

            // $this->addFlash('success', "You win");
            return $this->redirectToRoute('app_main_contact');
        }


        return $this->render('main/contact.html.twig', compact('contactForm'));
    }
}
