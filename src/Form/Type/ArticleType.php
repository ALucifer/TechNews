<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 02/03/2018
 * Time: 16:08
 */

namespace App\Form\Type;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre de l\'article'
                ]
            ])
            ->add('category', EntityType::class,[
                'class' => Category::class,
                //'choices' => $categories,
                'choice_label' => 'libelle',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre contenu ici'
                ]])
            ->add('featuredImage', FileType::class,[
                'required' => false,
                'attr' => [
                    'class' =>  'dropify'
                ]
            ])
            ->add('special', CheckboxType::class,[
                'required' => false
            ])
            ->add('spotlight', CheckboxType::class,[
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            /*'choices' => [
                'categories'
            ]*/
        ]);
    }

}