<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email.',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                        'mode' => 'html5',
                    ]),
                ],
            ])
            ->add('prospect_message', TextareaType::class, [
                'label' => 'Message',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $messagePlaceholder,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "required" => true,
        ]);
    }
}
