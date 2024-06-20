<?php

namespace App\Form;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $messagePlaceholder = "Merci de donner un maximum de détail sur le projet afin que je puisse évaluer correctement la charge de travail que représente votre projet. Cela permettra aussi d'avoir un premier chiffrage au plus prêt du devis final.";
        $builder
            ->add('prospect_name', TextType::class, [
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre nom et prénom',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner votre nom et prénom',
                    ]),
                    new Assert\Regex('^\w+\s+\w+^', 'Veuillez renseigner votre nom et prénom'),
                ]
            ])
            ->add('prospect_mail', EmailType::class, [
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez adresse votre email',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une adresse email',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide',
                        'mode' => 'html5',
                    ]),
                    new Assert\Regex('^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$^', "Point (.) manquant")
                ],
            ])
            ->add('prospect_message', TextareaType::class, [
                'label' => 'Message',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $messagePlaceholder,
                    'minlength' => 10,
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un message',
                    ]),
                    new Assert\Length(
                        min: 10,
                        minMessage: "Veuillez allonger votre texte pour qu'il comporte au moins {{ limit }} caractères",
                    ),
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "required" => true,
        ]);
    }
}
