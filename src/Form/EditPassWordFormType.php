<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditPassWordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('password',PasswordType::class,[
                'attr' => [
                
                    'placeholder' => 'Nouveau mot de passe '
                ],
                'label'=>false,
                'mapped'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe s\'il vous plait',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'votre mot de passe doit contenir au moins {{ limit }} caractères ',

                        'max' => 4096,
                    ])],

            ])
            ->add('password_confirm',PasswordType::class,[
                'attr' => [
                
                    'placeholder' => 'Confirmer le Mot de passe'
                ],
                'label'=>false,                
                'mapped'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe s\'il vous plait',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'votre mot de passe doit contenir au moins {{ limit }} caractères ',

                        'max' => 4096,
                    ])],

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Users::class,
        ]);
    }
}
