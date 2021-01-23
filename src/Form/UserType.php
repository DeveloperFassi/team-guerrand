<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
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
            ->add('roles', ChoiceType::class, [
                    'choices'=> [
                        'Utilisateur'=> 'ROLE_USER',
                        'Admin'=>'ROLE_ADMIN',
                        'Super Admin' => 'ROLE_SUPER_ADMIN'
                    ],
                'label'=> 'Roles :',
                'mapped'=> true,
                'expanded'=> false,
                'multiple'=>false
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
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image valide, trouduq.',
                    ])
                ],

            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
