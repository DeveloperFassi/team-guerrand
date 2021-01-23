<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, [
                'label' => 'Nom d\'utilisateur :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nom d\'utilisateur obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Nom d\'utilisateur doit comporter au moin {{limit}} caractère'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe :',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Rentre un mot de passe avant que je rentre dedans :)',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ton mot de passe doit comporter au moins {{ limit }} caractèrs',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :'
            ])
            ->add('photo', FileType::class, [
                'label'     => 'Ton avatar ou ce que tu veux :',
                'mapped'    => false,
                'required'  => false,
                'constraints' => [
                    new File([
                        'maxSize' => '8M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image valide, trouduq.',
                    ])
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}