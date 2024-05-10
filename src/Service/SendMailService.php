<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SendMailService
{
    private $mailer;
    private $parameterBag;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
    }

    public function send(
        string $to,
        string $subject,
        string $template,
        array $context,
        string $replyTo = null,
        string $from = null,
    ): void {
        if (!$from) {
            $from = $this->parameterBag->get("SHIPPING_MAIL");
        }
        $sender = new Address($from, 'Rostand dev');
        // On crée le mail 
        $email = (new TemplatedEmail())
            ->from($sender)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);
        if ($replyTo) {
            $email->replyTo($replyTo);
        }

        // On envoie le mail 
        $this->mailer->send($email);
    }
}
