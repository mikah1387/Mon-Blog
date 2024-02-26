<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Posts;

use App\Repository\CategoriesRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Titre de l\'article'
            ])
            ->add('content', HiddenType::class,[

                'attr'=>[
                    'class'=>'input_hidden'
                ]
                
            ])
            
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                 'choice_label' => 'name',
                 'multiple' => true,
                 'mapped'=>false,
                 'group_by'=>'parent.name',
                 'query_builder'=>function(CategoriesRepository $cr){

                 return $cr->createQueryBuilder('c')
                           -> where('c.parent IS NOT NULL')
                           ->orderBy('c.name','ASC');
             }
            ])
            ->add('image', FileType::class,[
                'multiple'=>false,
                'mapped'=>false,
                'required'=>false,
             
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
