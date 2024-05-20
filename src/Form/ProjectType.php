<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Stack;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectType extends AbstractType
{
    private string $currentRoute;
    public function __construct(RequestStack $requestStack)
    {
        $this->currentRoute = $requestStack->getCurrentRequest()->attributes->get('_route');
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $filesConstraints = [
            new Assert\Image(
                maxSize: '10240K',
                mimeTypes: [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ]
            ),
        ];
        $requiredFiles = true;
        if ($this->currentRoute === 'admin_project_edit') {
            $requiredFiles = false;
        } else {
            $constraints[] = new Assert\NotBlank();
        }

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre du projet',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('url', UrlType::class, [
                'label' => 'Lien du projet',
                'attr' => [
                    'type' => 'url',
                    'class' => 'form-control',
                    'placeholder' => 'Lien du projet',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Url(
                        protocols: ['https'],
                        message: 'L\'URL "{{ value }}" n\'est pas une URL valide.'
                    )
                ]
            ])
            ->add('urlType', ChoiceType::class, [
                'label' => 'Type de ressource',
                'placeholder' => 'Type de lien',
                'choices'  => [
                    'Website' => "website",
                    'Github' => "github",
                ],
                'attr' => [
                    'class' => 'form-select form-select-sm',
                    'placeholder' => 'Lien du projet',
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Sommaire',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sommaire',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('thumbnail', FileType::class, [
                'mapped' => false,
                'label' => 'Vignette',
                'attr' => [
                    'accept' => '.jpeg,.jpg,.png,.webp',
                    'class' => 'form-control file-upload-info',
                    'placeholder' => 'Ajoutez la vignette',
                ],
                'constraints' => $filesConstraints,
                'required' => $requiredFiles,
            ])
            ->add('mobilePicture', FileType::class, [
                'mapped' => false,
                'label' => 'Image pour mobile',
                'attr' => [
                    'accept' => '.jpeg,.jpg,.png,.webp',
                    'class' => 'form-control file-upload-info',
                    'placeholder' => 'Ajoutez l\'image pour mobile',
                ],
                'constraints' => $filesConstraints,
                'required' => $requiredFiles,
            ])
            ->add('desktopPicture', FileType::class, [
                'mapped' => false,
                'label' => 'Image pour desktop',
                'attr' => [
                    'accept' => '.jpeg,.jpg,.png,.webp',
                    'class' => 'form-control file-upload-info',
                    'placeholder' => 'Ajoutez l\'image pour desktop',
                ],
                'constraints' => $filesConstraints,
                'required' => $requiredFiles,
            ])
            ->add('stacks', EntityType::class, [
                'class' => Stack::class,
                'choice_label' => 'id',
                'multiple' => true,
                'attr' => [
                    'class' => 'form-select form-select-sm',
                    'placeholder' => 'Stacks',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner l\url du projet',
                    ]),
                ],
                "required" => false
            ])
            ->add('description', CKEditorType::class, [
                'config_name' => 'rost'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
