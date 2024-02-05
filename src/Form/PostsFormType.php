<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Posts;
use App\Entity\Users;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                 'choice_label' => 'name',
                 'multiple' => true,
                 'mapped'=>false,
                 'group_by'=>'parent.name',
                 'query_builder'=>function     (CategoriesRepository $cr){

                 return $cr->createQueryBuilder('c')
                           -> where('c.parent IS NOT NULL')
                           ->orderBy('c.name','ASC');
             }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
